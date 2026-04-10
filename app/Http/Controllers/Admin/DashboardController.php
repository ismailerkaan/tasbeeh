<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentVersion;
use App\Models\Dua;
use App\Models\DuaCategory;
use App\Models\MobileUser;
use App\Models\MobileUserDevice;
use App\Models\MobileUserReadDua;
use App\Models\MobileUserZikirCount;
use App\Models\PushNotification;
use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $contentVersion = ContentVersion::current();
        $now = now();
        $startDate = $now->copy()->subDays(6)->startOfDay();
        $days = collect(range(0, 6))->map(fn (int $index): Carbon => $startDate->copy()->addDays($index));

        $newUsersByDay = MobileUser::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->groupBy('day')
            ->pluck('total', 'day');

        $syncsByDay = MobileUser::query()
            ->selectRaw('DATE(synced_at) as day, COUNT(*) as total')
            ->whereNotNull('synced_at')
            ->whereDate('synced_at', '>=', $startDate->toDateString())
            ->groupBy('day')
            ->pluck('total', 'day');

        $trendLabels = $days->map(fn (Carbon $day): string => $day->translatedFormat('d M'))->all();
        $trendUsers = $days->map(fn (Carbon $day): int => (int) ($newUsersByDay[$day->toDateString()] ?? 0))->all();
        $trendSyncs = $days->map(fn (Carbon $day): int => (int) ($syncsByDay[$day->toDateString()] ?? 0))->all();

        $cityDistribution = MobileUser::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(city), ''), 'Bilinmiyor') as city_label, COUNT(*) as total")
            ->groupBy('city_label')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $platformDistribution = MobileUserDevice::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(os), ''), 'Bilinmiyor') as os_label, COUNT(*) as total")
            ->groupBy('os_label')
            ->orderByDesc('total')
            ->get();

        $topZikirCounts = MobileUserZikirCount::query()
            ->selectRaw('content_id, SUM(count) as total')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topZikirNumericIds = $topZikirCounts
            ->pluck('content_id')
            ->filter(fn ($contentId): bool => ctype_digit((string) $contentId))
            ->map(fn ($contentId): int => (int) $contentId)
            ->values();

        $zikirTitles = Zikir::query()
            ->select(['id', 'zikir'])
            ->whereIn('id', $topZikirNumericIds->all())
            ->get()
            ->keyBy(fn (Zikir $zikir): string => (string) $zikir->id);

        $topZikirLabels = $topZikirCounts
            ->map(function ($row) use ($zikirTitles): string {
                $contentId = (string) $row->content_id;
                $title = $zikirTitles->get($contentId)?->zikir;

                return $title ?: "ID: {$contentId}";
            })
            ->all();

        $topZikirValues = $topZikirCounts
            ->pluck('total')
            ->map(fn ($value): int => (int) $value)
            ->all();

        $mobileUsersCount = MobileUser::query()->count();
        $notificationOptInCount = MobileUser::query()->where('is_opt_in', true)->count();
        $totalZikirCount = (int) MobileUser::query()->sum('total_zikir_count');

        $pushQueuedCount = PushNotification::query()->where('status', PushNotification::STATUS_QUEUED)->count();
        $pushSentCount = PushNotification::query()->where('status', PushNotification::STATUS_SENT)->count();
        $pushFailedCount = PushNotification::query()->where('status', PushNotification::STATUS_FAILED)->count();
        $pushCanceledCount = PushNotification::query()->where('status', PushNotification::STATUS_CANCELED)->count();
        $totalReadDuasCount = MobileUserReadDua::query()->count();

        $versionAdoption = [
            'zikir' => $this->buildVersionAdoption(
                $mobileUsersCount,
                MobileUser::query()->where('zikir_version', $contentVersion->zikir_version)->count()
            ),
            'dua' => $this->buildVersionAdoption(
                $mobileUsersCount,
                MobileUser::query()->where('dua_version', $contentVersion->dua_version)->count()
            ),
            'prayer_times' => $this->buildVersionAdoption(
                $mobileUsersCount,
                MobileUser::query()->where('prayer_times_version', $contentVersion->prayer_times_version)->count()
            ),
        ];

        $topUsers = MobileUser::query()
            ->with('lastZikir')
            ->orderByDesc('total_zikir_count')
            ->orderByDesc('synced_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'contentVersion' => $contentVersion,
            'stats' => [
                'mobile_users' => $mobileUsersCount,
                'opt_in_rate' => $mobileUsersCount > 0 ? round(($notificationOptInCount / $mobileUsersCount) * 100, 1) : 0.0,
                'total_zikir_count' => $totalZikirCount,
                'total_read_duas' => $totalReadDuasCount,
                'queued_notifications' => $pushQueuedCount,
                'sent_notifications' => $pushSentCount,
                'failed_notifications' => $pushFailedCount,
                'canceled_notifications' => $pushCanceledCount,
                'zikir_categories' => ZikirCategory::query()->where('is_active', true)->count(),
                'zikirs' => Zikir::query()->count(),
                'dua_categories' => DuaCategory::query()->where('is_active', true)->count(),
                'duas' => Dua::query()->where('is_active', true)->count(),
                'last_sync_at' => MobileUser::query()->max('synced_at'),
            ],
            'versionAdoption' => $versionAdoption,
            'trendLabels' => $trendLabels,
            'trendUsers' => $trendUsers,
            'trendSyncs' => $trendSyncs,
            'cityLabels' => $cityDistribution->pluck('city_label')->all(),
            'cityValues' => $cityDistribution->pluck('total')->map(fn ($value): int => (int) $value)->all(),
            'platformLabels' => $platformDistribution->pluck('os_label')->all(),
            'platformValues' => $platformDistribution->pluck('total')->map(fn ($value): int => (int) $value)->all(),
            'topZikirLabels' => $topZikirLabels,
            'topZikirValues' => $topZikirValues,
            'topUsers' => $topUsers,
        ]);
    }

    /**
     * @return array{up_to_date_count: int, outdated_count: int, up_to_date_rate: float}
     */
    private function buildVersionAdoption(int $totalUsersCount, int $upToDateCount): array
    {
        if ($totalUsersCount <= 0) {
            return [
                'up_to_date_count' => 0,
                'outdated_count' => 0,
                'up_to_date_rate' => 0.0,
            ];
        }

        return [
            'up_to_date_count' => $upToDateCount,
            'outdated_count' => max($totalUsersCount - $upToDateCount, 0),
            'up_to_date_rate' => round(($upToDateCount / $totalUsersCount) * 100, 1),
        ];
    }
}
