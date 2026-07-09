<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestLevelRequest;
use App\Http\Requests\Admin\UpdateTestLevelRequest;
use App\Models\TestLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestLevelController extends Controller
{
    public function index(): View
    {
        return view('admin.test-levels.index', [
            'testLevels' => TestLevel::query()
                ->withCount('questions')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.test-levels.create');
    }

    public function store(StoreTestLevelRequest $request): RedirectResponse
    {
        TestLevel::query()->create($request->validated());

        return to_route('admin.test-levels.index')
            ->with('status', 'Test seviyesi oluşturuldu.');
    }

    public function edit(TestLevel $testLevel): View
    {
        return view('admin.test-levels.edit', [
            'testLevel' => $testLevel,
        ]);
    }

    public function update(UpdateTestLevelRequest $request, TestLevel $testLevel): RedirectResponse
    {
        $testLevel->update($request->validated());

        return to_route('admin.test-levels.index')
            ->with('status', 'Test seviyesi güncellendi.');
    }

    public function destroy(TestLevel $testLevel): RedirectResponse
    {
        $testLevel->delete();

        return to_route('admin.test-levels.index')
            ->with('status', 'Test seviyesi silindi.');
    }
}