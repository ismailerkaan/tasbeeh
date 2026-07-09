<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestQuestionRequest;
use App\Http\Requests\Admin\UpdateTestQuestionRequest;
use App\Models\TestLevel;
use App\Models\TestQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestQuestionController extends Controller
{
    public function index(): View
    {
        return view('admin.test-questions.index', [
            'testQuestions' => TestQuestion::query()
                ->with('level')
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.test-questions.create', [
            'levels' => $this->selectableLevels(),
        ]);
    }

    public function store(StoreTestQuestionRequest $request): RedirectResponse
    {
        TestQuestion::query()->create($request->validated());

        return to_route('admin.test-questions.index')
            ->with('status', 'Test sorusu oluşturuldu.');
    }

    public function edit(TestQuestion $testQuestion): View
    {
        return view('admin.test-questions.edit', [
            'testQuestion' => $testQuestion,
            'levels' => $this->selectableLevels($testQuestion->test_level_id),
        ]);
    }

    public function update(UpdateTestQuestionRequest $request, TestQuestion $testQuestion): RedirectResponse
    {
        $testQuestion->update($request->validated());

        return to_route('admin.test-questions.index')
            ->with('status', 'Test sorusu güncellendi.');
    }

    public function destroy(TestQuestion $testQuestion): RedirectResponse
    {
        $testQuestion->delete();

        return to_route('admin.test-questions.index')
            ->with('status', 'Test sorusu silindi.');
    }

    private function selectableLevels(?int $selectedLevelId = null): Collection
    {
        return TestLevel::query()
            ->where(function ($query) use ($selectedLevelId): void {
                $query->where('is_active', true);

                if ($selectedLevelId !== null) {
                    $query->orWhere('id', $selectedLevelId);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}