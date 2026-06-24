<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHadisRequest;
use App\Http\Requests\Admin\UpdateHadisRequest;
use App\Models\Hadis;
use App\Models\HadisCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HadisController extends Controller
{
    public function index(): View
    {
        return view('admin.hadises.index', [
            'hadises' => Hadis::query()
                ->with('category')
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.hadises.create', [
            'categories' => $this->selectableCategories(),
        ]);
    }

    public function store(StoreHadisRequest $request): RedirectResponse
    {
        Hadis::query()->create($request->validated());

        return to_route('admin.hadises.index')
            ->with('status', 'Hadis oluşturuldu.');
    }

    public function edit(Hadis $hadis): View
    {
        return view('admin.hadises.edit', [
            'hadis' => $hadis,
            'categories' => $this->selectableCategories($hadis->hadis_category_id),
        ]);
    }

    public function update(UpdateHadisRequest $request, Hadis $hadis): RedirectResponse
    {
        $hadis->update($request->validated());

        return to_route('admin.hadises.index')
            ->with('status', 'Hadis güncellendi.');
    }

    public function destroy(Hadis $hadis): RedirectResponse
    {
        $hadis->delete();

        return to_route('admin.hadises.index')
            ->with('status', 'Hadis silindi.');
    }

    private function selectableCategories(?int $selectedCategoryId = null): Collection
    {
        return HadisCategory::query()
            ->where(function ($query) use ($selectedCategoryId): void {
                $query->where('is_active', true);

                if ($selectedCategoryId !== null) {
                    $query->orWhere('id', $selectedCategoryId);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
