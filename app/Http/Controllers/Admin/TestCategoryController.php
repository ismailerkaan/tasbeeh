<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestCategoryRequest;
use App\Http\Requests\Admin\UpdateTestCategoryRequest;
use App\Models\TestCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.test-categories.index', [
            'testCategories' => TestCategory::query()
                ->withCount('levels')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.test-categories.create');
    }

    public function store(StoreTestCategoryRequest $request): RedirectResponse
    {
        TestCategory::query()->create($request->validated());

        return to_route('admin.test-categories.index')
            ->with('status', 'Test kategorisi oluşturuldu.');
    }

    public function edit(TestCategory $testCategory): View
    {
        return view('admin.test-categories.edit', [
            'testCategory' => $testCategory,
        ]);
    }

    public function update(UpdateTestCategoryRequest $request, TestCategory $testCategory): RedirectResponse
    {
        $testCategory->update($request->validated());

        return to_route('admin.test-categories.index')
            ->with('status', 'Test kategorisi güncellendi.');
    }

    public function destroy(TestCategory $testCategory): RedirectResponse
    {
        $testCategory->delete();

        return to_route('admin.test-categories.index')
            ->with('status', 'Test kategorisi silindi.');
    }
}