<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDuaRequest;
use App\Http\Requests\Admin\UpdateDuaRequest;
use App\Models\Dua;
use App\Models\DuaCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DuaController extends Controller
{
    public function index(): View
    {
        return view('admin.duas.index', [
            'duas' => Dua::query()
                ->with('category')
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.duas.create', [
            'categories' => $this->selectableCategories(),
        ]);
    }

    public function store(StoreDuaRequest $request): RedirectResponse
    {
        Dua::query()->create($request->validated());

        return to_route('admin.duas.index')
            ->with('status', 'Dua oluşturuldu.');
    }

    public function edit(Dua $dua): View
    {
        return view('admin.duas.edit', [
            'dua' => $dua,
            'categories' => $this->selectableCategories($dua->dua_category_id),
        ]);
    }

    public function update(UpdateDuaRequest $request, Dua $dua): RedirectResponse
    {
        $dua->update($request->validated());

        return to_route('admin.duas.index')
            ->with('status', 'Dua güncellendi.');
    }

    public function destroy(Dua $dua): RedirectResponse
    {
        $dua->delete();

        return to_route('admin.duas.index')
            ->with('status', 'Dua silindi.');
    }

    private function selectableCategories(?int $selectedCategoryId = null): Collection
    {
        return DuaCategory::query()
            ->where(function ($query) use ($selectedCategoryId): void {
                $query->where('is_active', true);

                if ($selectedCategoryId !== null) {
                    $query->orWhere('id', $selectedCategoryId);
                }
            })
            ->orderBy('name')
            ->get();
    }
}
