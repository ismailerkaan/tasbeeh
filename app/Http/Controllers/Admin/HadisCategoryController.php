<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHadisCategoryRequest;
use App\Http\Requests\Admin\UpdateHadisCategoryRequest;
use App\Models\HadisCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HadisCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.hadis-categories.index', [
            'hadisCategories' => HadisCategory::query()
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.hadis-categories.create');
    }

    public function store(StoreHadisCategoryRequest $request): RedirectResponse
    {
        HadisCategory::query()->create($request->validated());

        return to_route('admin.hadis-categories.index')
            ->with('status', 'Hadis kategorisi oluşturuldu.');
    }

    public function edit(HadisCategory $hadisCategory): View
    {
        return view('admin.hadis-categories.edit', [
            'hadisCategory' => $hadisCategory,
        ]);
    }

    public function update(UpdateHadisCategoryRequest $request, HadisCategory $hadisCategory): RedirectResponse
    {
        $hadisCategory->update($request->validated());

        return to_route('admin.hadis-categories.index')
            ->with('status', 'Hadis kategorisi güncellendi.');
    }

    public function destroy(HadisCategory $hadisCategory): RedirectResponse
    {
        $hadisCategory->delete();

        return to_route('admin.hadis-categories.index')
            ->with('status', 'Hadis kategorisi silindi.');
    }
}
