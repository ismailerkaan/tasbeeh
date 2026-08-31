<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMobileUserRequest;
use App\Http\Requests\Admin\StoreMobileUserPushNotificationRequest;
use App\Http\Requests\Admin\UpdateMobileUserRequest;
use App\Jobs\SendPushNotificationJob;
use App\Models\Dua;
use App\Models\MobileUser;
use App\Models\PushNotification;
use App\Models\Zikir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class MobileUserController extends Controller
{
    public function index(Request $request): View
    {
        $sortableColumns = [
            'synced_at',
            'id',
        ];

        $sort = (string) $request->query('sort', 'id');
        $direction = strtolower((string) $request->query('direction', 'desc'));

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'id';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        return view('admin.mobile-users.index', [
            'mobileUsers' => MobileUser::query()
                ->with(['lastZikir', 'testStat'])
                ->withCount(['devices', 'readZikirs', 'readDuas', 'testRuns'])
                ->withMax('devices as last_login_at', 'last_seen_at')
                ->orderBy($sort, $direction)
                ->paginate(20)
                ->withQueryString(),
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): View
    {
        return view('admin.mobile-users.create');
    }

    public function show(MobileUser $mobileUser): View
    {
        $mobileUser->load([
            'lastZikir',
            'devices' => fn ($query) => $query->latest('last_seen_at')->latest('id'),
            'zikirCounts' => fn ($query) => $query->orderByDesc('count')->latest('id'),
            'readZikirs' => fn ($query) => $query->latest('id'),
            'readDuas' => fn ($query) => $query->latest('id'),
            'testStat',
            'testRuns' => fn ($query) => $query
                ->with([
                    'level',
                    'answers' => fn ($answerQuery) => $answerQuery->with('question')->orderBy('question_order')->orderBy('id'),
                ])
                ->latest('ended_at')
                ->latest('id')
                ->limit(20),
        ]);

        $mobileUser->loadCount(['devices', 'readZikirs', 'readDuas', 'testRuns']);
        $mobileUser->loadMax('devices as last_login_at', 'last_seen_at');

        $zikirCountIds = $mobileUser->zikirCounts
            ->pluck('content_id')
            ->filter(fn ($contentId): bool => filled($contentId))
            ->unique()
            ->values();

        $readZikirIds = $mobileUser->readZikirs
            ->pluck('content_id')
            ->filter(fn ($contentId): bool => filled($contentId))
            ->unique()
            ->values();

        $readDuaIds = $mobileUser->readDuas
            ->pluck('content_id')
            ->filter(fn ($contentId): bool => filled($contentId))
            ->unique()
            ->values();

        $allZikirIds = $zikirCountIds->merge($readZikirIds)->unique()->values();

        $readZikirNumericIds = $allZikirIds
            ->filter(fn ($contentId): bool => ctype_digit((string) $contentId))
            ->map(fn ($contentId): int => (int) $contentId)
            ->values();

        $readDuaNumericIds = $readDuaIds
            ->filter(fn ($contentId): bool => ctype_digit((string) $contentId))
            ->map(fn ($contentId): int => (int) $contentId)
            ->values();

        $resolvedZikirs = Zikir::query()
            ->select(['id', 'zikir'])
            ->whereIn('id', $readZikirNumericIds->all())
            ->get()
            ->keyBy(fn (Zikir $zikir): string => (string) $zikir->id);

        $resolvedDuas = Dua::query()
            ->select(['id', 'dua'])
            ->whereIn('id', $readDuaNumericIds->all())
            ->get()
            ->keyBy(fn (Dua $dua): string => (string) $dua->id);

        $readZikirItems = $mobileUser->zikirCounts->map(function ($item) use ($resolvedZikirs): array {
            $contentId = (string) $item->content_id;

            return [
                'content_id' => $contentId,
                'title' => $resolvedZikirs->get($contentId)?->zikir,
                'count' => (int) $item->count,
                'created_at' => $item->updated_at ?? $item->created_at,
            ];
        })->all();

        if ($readZikirItems === []) {
            $readZikirItems = $mobileUser->readZikirs
                ->map(function ($item) use ($resolvedZikirs): array {
                    $contentId = (string) $item->content_id;

                    return [
                        'content_id' => $contentId,
                        'title' => $resolvedZikirs->get($contentId)?->zikir,
                        'count' => null,
                        'created_at' => $item->created_at,
                    ];
                })
                ->all();
        }

        $readDuaItems = $mobileUser->readDuas
            ->map(function ($item) use ($resolvedDuas): array {
                $contentId = (string) $item->content_id;

                return [
                    'content_id' => $contentId,
                    'title' => $resolvedDuas->get($contentId)?->dua,
                    'created_at' => $item->created_at,
                ];
            })
            ->all();

        $customZikirItems = \Illuminate\Support\Facades\DB::table('mobile_user_custom_zikirs')
            ->where('mobile_user_id', $mobileUser->id)
            ->orderByDesc('updated_at')
            ->get(['content_id', 'name', 'target', 'count', 'updated_at'])
            ->map(fn ($item): array => [
                'content_id' => (string) $item->content_id,
                'name' => (string) $item->name,
                'target' => (int) $item->target,
                'count' => (int) $item->count,
                'updated_at' => $item->updated_at ? \Illuminate\Support\Carbon::parse($item->updated_at) : null,
            ])
            ->all();

        return view('admin.mobile-users.show', [
            'mobileUser' => $mobileUser,
            'readZikirItems' => $readZikirItems,
            'readDuaItems' => $readDuaItems,
            'customZikirItems' => $customZikirItems,
        ]);
    }

    public function store(StoreMobileUserRequest $request): RedirectResponse
    {
        MobileUser::query()->create($request->validated());

        return to_route('admin.mobile-users.index')
            ->with('status', 'Kullanıcı oluşturuldu.');
    }

    public function edit(MobileUser $mobileUser): View
    {
        return view('admin.mobile-users.edit', [
            'mobileUser' => $mobileUser,
        ]);
    }

    public function update(UpdateMobileUserRequest $request, MobileUser $mobileUser): RedirectResponse
    {
        $mobileUser->update($request->validated());

        return to_route('admin.mobile-users.index')
            ->with('status', 'Kullanıcı güncellendi.');
    }

    public function sendPushNotification(StoreMobileUserPushNotificationRequest $request, MobileUser $mobileUser): RedirectResponse
    {
        $targetUserIdentifier = trim((string) $mobileUser->external_user_id);

        if ($targetUserIdentifier === '') {
            return back()
                ->withInput()
                ->with('error', 'Bu kullanıcının kullanıcı kimliği olmadığı için bildirim gönderilemedi.');
        }

        $pushNotification = PushNotification::query()->create([
            'title' => $request->validated('title'),
            'body' => $request->validated('body'),
            'target_type' => PushNotification::TARGET_USER,
            'target_user_identifier' => $targetUserIdentifier,
            'data' => $request->payloadData(),
            'status' => PushNotification::STATUS_QUEUED,
        ]);

        try {
            SendPushNotificationJob::dispatchSync($pushNotification->id);
        } catch (Throwable $throwable) {
            report($throwable);

            return back()
                ->withInput()
                ->with('error', 'Gönderim hatası: '.$throwable->getMessage());
        }

        $pushNotification->refresh();

        if ($pushNotification->status === PushNotification::STATUS_FAILED) {
            return back()
                ->withInput()
                ->with('error', 'Bildirim gönderilemedi: '.($pushNotification->error_message ?: 'Bilinmeyen hata'));
        }

        return back()
            ->with('status', "Bildirim gönderildi. Başarılı: {$pushNotification->success_count}, Hatalı: {$pushNotification->failed_count}");
    }

    public function destroy(MobileUser $mobileUser): RedirectResponse
    {
        $mobileUser->delete();

        return to_route('admin.mobile-users.index')
            ->with('status', 'Kullanıcı silindi.');
    }
}
