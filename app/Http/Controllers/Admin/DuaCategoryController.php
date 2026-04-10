<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDuaCategoryRequest;
use App\Http\Requests\Admin\UpdateDuaCategoryRequest;
use App\Models\DuaCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DuaCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.dua-categories.index', [
            'duaCategories' => DuaCategory::query()
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.dua-categories.create');
    }

    public function store(StoreDuaCategoryRequest $request): RedirectResponse
    {
        DuaCategory::query()->create($request->validated());

        return to_route('admin.dua-categories.index')
            ->with('status', 'Dua kategorisi oluşturuldu.');
    }

    public function edit(DuaCategory $duaCategory): View
    {
        return view('admin.dua-categories.edit', [
            'duaCategory' => $duaCategory,
        ]);
    }

    public function update(UpdateDuaCategoryRequest $request, DuaCategory $duaCategory): RedirectResponse
    {
        $duaCategory->update($request->validated());

        return to_route('admin.dua-categories.index')
            ->with('status', 'Dua kategorisi güncellendi.');
    }

    public function destroy(DuaCategory $duaCategory): RedirectResponse
    {
        $duaCategory->delete();

        return to_route('admin.dua-categories.index')
            ->with('status', 'Dua kategorisi silindi.');
    }
}
