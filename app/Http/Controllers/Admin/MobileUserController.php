<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMobileUserRequest;
use App\Http\Requests\Admin\UpdateMobileUserRequest;
use App\Models\Dua;
use App\Models\MobileUser;
use App\Models\Zikir;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MobileUserController extends Controller
{
    public function index(): View
    {
        return view('admin.mobile-users.index', [
            'mobileUsers' => MobileUser::query()
                ->with('lastZikir')
                ->withCount(['devices', 'readZikirs', 'readDuas'])
                ->withMax('devices as last_login_at', 'last_seen_at')
                ->latest('id')
                ->paginate(20),
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
        ]);

        $mobileUser->loadCount(['devices', 'readZikirs', 'readDuas']);
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

        return view('admin.mobile-users.show', [
            'mobileUser' => $mobileUser,
            'readZikirItems' => $readZikirItems,
            'readDuaItems' => $readDuaItems,
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

    public function destroy(MobileUser $mobileUser): RedirectResponse
    {
        $mobileUser->delete();

        return to_route('admin.mobile-users.index')
            ->with('status', 'Kullanıcı silindi.');
    }
}
