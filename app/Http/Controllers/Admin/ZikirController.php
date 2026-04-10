<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreZikirRequest;
use App\Http\Requests\Admin\UpdateZikirRequest;
use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ZikirController extends Controller
{
    public function index(): View
    {
        return view('admin.zikirs.index', [
            'zikirs' => Zikir::query()
                ->with('category')
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.zikirs.create', [
            'categories' => $this->selectableCategories(),
        ]);
    }

    public function store(StoreZikirRequest $request): RedirectResponse
    {
        Zikir::query()->create($request->validated());

        return to_route('admin.zikirs.index')
            ->with('status', 'Zikir oluşturuldu.');
    }

    public function edit(Zikir $zikir): View
    {
        return view('admin.zikirs.edit', [
            'zikir' => $zikir,
            'categories' => $this->selectableCategories($zikir->zikir_category_id),
        ]);
    }

    public function update(UpdateZikirRequest $request, Zikir $zikir): RedirectResponse
    {
        $zikir->update($request->validated());

        return to_route('admin.zikirs.index')
            ->with('status', 'Zikir güncellendi.');
    }

    public function destroy(Zikir $zikir): RedirectResponse
    {
        $zikir->delete();

        return to_route('admin.zikirs.index')
            ->with('status', 'Zikir silindi.');
    }

    private function selectableCategories(?int $selectedCategoryId = null): Collection
    {
        return ZikirCategory::query()
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
