<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SyncUserStateRequest;
use App\Models\ContentVersion;
use App\Models\DevicePushToken;
use App\Models\DuaCategory;
use App\Models\HadisCategory;
use App\Models\MobileUser;
use App\Models\MobileUserDevice;
use App\Models\MobileUserLastZikir;
use App\Models\ZikirCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SyncUserStateController extends Controller
{
    public function __invoke(SyncUserStateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $syncedAt = Carbon::parse((string) $validated['updatedAt']);
        $readZikirIds = $this->normalizeIdList($validated['readZikirs'] ?? []);
        $zikirCounts = $this->normalizeCountMap($validated['zikirCounts'] ?? []);
        $readDuaIds = $this->normalizeIdList($validated['readDuas'] ?? []);
        $customZikirs = collect(is_array($validated['customZikirs'] ?? null) ? $validated['customZikirs'] : [])
            ->filter(fn ($item): bool => is_array($item) && isset($item['id'], $item['name']))
            ->map(function (array $item): array {
                return [
                    'content_id' => trim((string) $item['id']),
                    'name' => trim((string) $item['name']),
                    'target' => max(1, (int) ($item['target'] ?? 33)),
                    'count' => max(0, (int) ($item['count'] ?? 0)),
                ];
            })
            ->filter(fn (array $item): bool => $item['content_id'] !== '' && $item['name'] !== '')
            ->values()
            ->all();
        $clientVersions = [
            'zikir_version' => (int) ($validated['zikirVersion'] ?? 1),
            'dua_version' => (int) ($validated['duaVersion'] ?? 1),
            'hadis_version' => (int) ($validated['hadisVersion'] ?? 1),
            'prayer_times_version' => (int) ($validated['prayerTimesVersion'] ?? 1),
        ];
        $streakPayload = is_array($validated['streak'] ?? null) ? $validated['streak'] : [];
        $dailyActivitySummary = collect(is_array($validated['dailyActivitySummary'] ?? null) ? $validated['dailyActivitySummary'] : [])
            ->filter(fn ($item): bool => is_array($item) && isset($item['date']))
            ->map(function (array $item): array {
                return [
                    'date' => (string) $item['date'],
                    'totalCount' => (int) ($item['totalCount'] ?? 0),
                    'completedDailyZikr' => (bool) ($item['completedDailyZikr'] ?? false),
                ];
            })
            ->sortBy('date')
            ->values()
            ->take(-60)
            ->values()
            ->all();
        $contentVersion = ContentVersion::current();
        $serverVersions = [
            'zikir_version' => $contentVersion->zikir_version,
            'dua_version' => $contentVersion->dua_version,
            'hadis_version' => $contentVersion->hadis_version,
            'prayer_times_version' => $contentVersion->prayer_times_version,
        ];
        $changedModules = collect(ContentVersion::MODULE_COLUMN_MAP)
            ->filter(fn (string $column): bool => $clientVersions[$column] < $serverVersions[$column])
            ->keys()
            ->values();
        $contentPayload = $this->buildChangedContentPayload($changedModules, $serverVersions, $contentVersion);

        DB::transaction(function () use ($validated, $syncedAt, $readZikirIds, $zikirCounts, $readDuaIds, $customZikirs, $clientVersions, $streakPayload, $dailyActivitySummary): void {
            /** @var MobileUser $mobileUser */
            $mobileUser = MobileUser::query()->updateOrCreate(
                ['external_user_id' => $validated['userId']],
                [
                    'city' => data_get($validated, 'location.city'),
                    'district' => data_get($validated, 'location.district'),
                    'is_opt_in' => (bool) $validated['isOptIn'],
                    'total_zikir_count' => (int) $validated['totalZikirCount'],
                    'current_streak' => (int) ($streakPayload['current'] ?? 0),
                    'best_streak' => (int) ($streakPayload['best'] ?? 0),
                    'total_active_days' => (int) ($streakPayload['totalActiveDays'] ?? 0),
                    'last_active_date' => $streakPayload['lastActiveDate'] ?? null,
                    'daily_activity_summary' => $dailyActivitySummary === [] ? null : $dailyActivitySummary,
                    'zikir_version' => $clientVersions['zikir_version'],
                    'dua_version' => $clientVersions['dua_version'],
                    'hadis_version' => $clientVersions['hadis_version'],
                    'prayer_times_version' => $clientVersions['prayer_times_version'],
                    'synced_at' => $syncedAt,
                ]
            );

            $fcmToken = trim((string) ($validated['fcmToken'] ?? ''));
            if ($fcmToken !== '') {
                MobileUserDevice::query()->updateOrCreate(
                    ['fcm_token' => $fcmToken],
                    [
                        'mobile_user_id' => $mobileUser->id,
                        'device_name' => data_get($validated, 'device.name'),
                        'device_model' => data_get($validated, 'device.model'),
                        'os' => data_get($validated, 'device.os'),
                        'os_version' => data_get($validated, 'device.version'),
                        'is_active' => (bool) $validated['isOptIn'],
                        'last_seen_at' => now(),
                    ]
                );

                DevicePushToken::query()->updateOrCreate(
                    ['token' => $fcmToken],
                    [
                        'user_identifier' => $validated['userId'],
                        'platform' => strtolower((string) data_get($validated, 'device.os', '')),
                        'is_active' => (bool) $validated['isOptIn'],
                        'last_seen_at' => now(),
                    ]
                );
            }

            $lastZikir = data_get($validated, 'lastZikir');

            if (is_array($lastZikir) && $lastZikir !== []) {
                MobileUserLastZikir::query()->updateOrCreate(
                    ['mobile_user_id' => $mobileUser->id],
                    [
                        'content_id' => (string) $lastZikir['id'],
                        'name' => (string) $lastZikir['name'],
                        'count' => (int) $lastZikir['count'],
                    ]
                );
            }

            DB::table('mobile_user_read_zikirs')
                ->where('mobile_user_id', $mobileUser->id)
                ->delete();

            if ($readZikirIds !== []) {
                DB::table('mobile_user_read_zikirs')->insert(
                    array_map(
                        fn (string $id): array => [
                            'mobile_user_id' => $mobileUser->id,
                            'content_id' => $id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        $readZikirIds
                    )
                );
            }

            DB::table('mobile_user_zikir_counts')
                ->where('mobile_user_id', $mobileUser->id)
                ->delete();

            if ($zikirCounts !== []) {
                DB::table('mobile_user_zikir_counts')->insert(
                    array_map(
                        fn (string $id, int $count): array => [
                            'mobile_user_id' => $mobileUser->id,
                            'content_id' => $id,
                            'count' => $count,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        array_keys($zikirCounts),
                        array_values($zikirCounts)
                    )
                );
            }

            DB::table('mobile_user_read_duas')
                ->where('mobile_user_id', $mobileUser->id)
                ->delete();

            if ($readDuaIds !== []) {
                DB::table('mobile_user_read_duas')->insert(
                    array_map(
                        fn (string $id): array => [
                            'mobile_user_id' => $mobileUser->id,
                            'content_id' => $id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        $readDuaIds
                    )
                );
            }

            DB::table('mobile_user_custom_zikirs')
                ->where('mobile_user_id', $mobileUser->id)
                ->delete();

            if ($customZikirs !== []) {
                DB::table('mobile_user_custom_zikirs')->insert(
                    array_map(
                        fn (array $item): array => [
                            'mobile_user_id' => $mobileUser->id,
                            'content_id' => $item['content_id'],
                            'name' => $item['name'],
                            'target' => $item['target'],
                            'count' => $item['count'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        $customZikirs
                    )
                );
            }
        });

        return response()->json([
            'message' => 'User state synchronized.',
            'userId' => $validated['userId'],
            'readZikirsCount' => count($readZikirIds),
            'zikirCountsTracked' => count($zikirCounts),
            'readDuasCount' => count($readDuaIds),
            'customZikirsCount' => count($customZikirs),
            'totalZikirCount' => (int) $validated['totalZikirCount'],
            'streak' => [
                'current' => (int) ($streakPayload['current'] ?? 0),
                'best' => (int) ($streakPayload['best'] ?? 0),
                'totalActiveDays' => (int) ($streakPayload['totalActiveDays'] ?? 0),
                'lastActiveDate' => $streakPayload['lastActiveDate'] ?? null,
            ],
            'versions' => $serverVersions,
            'has_updates' => $changedModules->isNotEmpty(),
            'changed_modules' => $changedModules,
            'content' => $contentPayload,
            'updatedAt' => $syncedAt->toIso8601String(),
        ]);
    }

    /**
     * @param  Collection<int, string>  $changedModules
     * @param  array{zikir_version: int, dua_version: int, hadis_version: int, prayer_times_version: int}  $serverVersions
     * @return array{zikir?: array{version: int, updated_at: ?string, data: array<int, array<string, mixed>>}, dua?: array{version: int, updated_at: ?string, data: array<int, array<string, mixed>>}, hadis?: array{version: int, updated_at: ?string, data: array<int, array<string, mixed>>}}
     */
    private function buildChangedContentPayload(Collection $changedModules, array $serverVersions, ContentVersion $contentVersion): array
    {
        $payload = [];

        if ($changedModules->contains('zikir')) {
            $payload['zikir'] = [
                'version' => $serverVersions['zikir_version'],
                'updated_at' => $contentVersion->updated_at?->toIso8601String(),
                'data' => $this->buildZikirData(),
            ];
        }

        if ($changedModules->contains('dua')) {
            $payload['dua'] = [
                'version' => $serverVersions['dua_version'],
                'updated_at' => $contentVersion->updated_at?->toIso8601String(),
                'data' => $this->buildDuaData(),
            ];
        }

        if ($changedModules->contains('hadis')) {
            $payload['hadis'] = [
                'version' => $serverVersions['hadis_version'],
                'updated_at' => $contentVersion->updated_at?->toIso8601String(),
                'data' => $this->buildHadisData(),
            ];
        }

        return $payload;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildZikirData(): array
    {
        return ZikirCategory::query()
            ->where('is_active', true)
            ->with(['zikirs' => fn ($query) => $query->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (ZikirCategory $category): array {
                return [
                    'kategori_adi' => $category->name,
                    'kategori_aciklama' => (string) ($category->description ?? ''),
                    'zikirler' => $category->zikirs->map(function ($zikir): array {
                        return [
                            'id' => (int) $zikir->id,
                            'zikir' => (string) $zikir->zikir,
                            'anlami' => (string) $zikir->anlami,
                            'adet' => (int) $zikir->hedef,
                            'fazileti' => (string) $zikir->fazileti,
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildDuaData(): array
    {
        return DuaCategory::query()
            ->where('is_active', true)
            ->with(['duas' => fn ($query) => $query->where('is_active', true)->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (DuaCategory $category): array {
                return [
                    'kategori' => $category->name,
                    'dualar' => $category->duas->map(function ($dua): array {
                        return [
                            'id' => (int) $dua->id,
                            'dua' => (string) $dua->dua,
                            'anlami' => (string) $dua->turkce_meali,
                            'kaynak' => (string) $dua->source,
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildHadisData(): array
    {
        return HadisCategory::query()
            ->where('is_active', true)
            ->with(['hadises' => fn ($query) => $query->where('is_active', true)->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (HadisCategory $category): array {
                return [
                    'kategori' => $category->name,
                    'hadisler' => $category->hadises->map(function ($hadis): array {
                        return [
                            'id' => (int) $hadis->id,
                            'hadis' => (string) $hadis->hadis,
                            'anlami' => (string) $hadis->turkce_meali,
                            'kaynak' => (string) $hadis->source,
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private function normalizeIdList(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(function ($value): string {
            return trim((string) $value);
        }, $values), fn (string $value): bool => $value !== '')));
    }

    /**
     * @return array<string, int>
     */
    private function normalizeCountMap(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        $normalized = [];

        foreach ($values as $contentId => $count) {
            $key = trim((string) $contentId);
            $safeCount = (int) $count;

            if ($key === '' || $safeCount <= 0) {
                continue;
            }

            $normalized[$key] = $safeCount;
        }

        return $normalized;
    }
}
