<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDailyZikrRequest;
use App\Http\Requests\Admin\UpdateDailyZikrRequest;
use App\Models\DailyZikr;
use App\Models\Zikir;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DailyZikrController extends Controller
{
    public function index(): View
    {
        return view('admin.daily-zikrs.index', [
            'dailyZikrs' => DailyZikr::query()
                ->with('zikir')
                ->latest('date')
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.daily-zikrs.create', [
            'zikirs' => $this->selectableZikirs(),
        ]);
    }

    public function store(StoreDailyZikrRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        DailyZikr::query()->create([
            'date' => $payload['date'],
            'locale' => $payload['locale'] ?? null,
            'title' => 'Bugunun zikri',
            'zikir_id' => (int) $payload['zikir_id'],
            'transliteration' => null,
            'meaning' => null,
            'virtue_short' => null,
            'count_suggestion' => null,
            'share_text' => null,
            'is_active' => (bool) ($payload['is_active'] ?? false),
        ]);

        return to_route('admin.daily-zikrs.index')
            ->with('status', 'Gunun zikri olusturuldu.');
    }

    public function edit(DailyZikr $daily_zikr): View
    {
        return view('admin.daily-zikrs.edit', [
            'dailyZikr' => $daily_zikr,
            'zikirs' => $this->selectableZikirs($daily_zikr->zikir_id),
        ]);
    }

    public function update(UpdateDailyZikrRequest $request, DailyZikr $daily_zikr): RedirectResponse
    {
        $payload = $request->validated();
        $daily_zikr->update([
            'date' => $payload['date'],
            'locale' => $payload['locale'] ?? null,
            'title' => 'Bugunun zikri',
            'zikir_id' => (int) $payload['zikir_id'],
            'transliteration' => null,
            'meaning' => null,
            'virtue_short' => null,
            'count_suggestion' => null,
            'share_text' => null,
            'is_active' => (bool) ($payload['is_active'] ?? false),
        ]);

        return to_route('admin.daily-zikrs.index')
            ->with('status', 'Gunun zikri guncellendi.');
    }

    public function destroy(DailyZikr $daily_zikr): RedirectResponse
    {
        $daily_zikr->delete();

        return to_route('admin.daily-zikrs.index')
            ->with('status', 'Gunun zikri silindi.');
    }

    private function selectableZikirs(?int $selectedZikirId = null): Collection
    {
        return Zikir::query()
            ->where(function ($query) use ($selectedZikirId): void {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('is_active', true));

                if ($selectedZikirId !== null) {
                    $query->orWhere('id', $selectedZikirId);
                }
            })
            ->orderBy('zikir')
            ->get();
    }
}
