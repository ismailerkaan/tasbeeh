<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreZikirCategoryRequest;
use App\Http\Requests\Admin\UpdateZikirCategoryRequest;
use App\Models\ZikirCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ZikirCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.zikir-categories.index', [
            'zikirCategories' => ZikirCategory::query()
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.zikir-categories.create');
    }

    public function store(StoreZikirCategoryRequest $request): RedirectResponse
    {
        ZikirCategory::query()->create($request->validated());

        return to_route('admin.zikir-categories.index')
            ->with('status', 'Zikir kategorisi oluşturuldu.');
    }

    public function edit(ZikirCategory $zikirCategory): View
    {
        return view('admin.zikir-categories.edit', [
            'zikirCategory' => $zikirCategory,
        ]);
    }

    public function update(UpdateZikirCategoryRequest $request, ZikirCategory $zikirCategory): RedirectResponse
    {
        $zikirCategory->update($request->validated());

        return to_route('admin.zikir-categories.index')
            ->with('status', 'Zikir kategorisi güncellendi.');
    }

    public function destroy(ZikirCategory $zikirCategory): RedirectResponse
    {
        $zikirCategory->delete();

        return to_route('admin.zikir-categories.index')
            ->with('status', 'Zikir kategorisi silindi.');
    }
}
