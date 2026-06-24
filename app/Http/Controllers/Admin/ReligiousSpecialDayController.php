<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReligiousSpecialDayRequest;
use App\Http\Requests\Admin\UpdateReligiousSpecialDayRequest;
use App\Models\ReligiousSpecialDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReligiousSpecialDayController extends Controller
{
    public function index(): View
    {
        return view('admin.religious-special-days.index', [
            'specialDays' => ReligiousSpecialDay::query()->orderBy('event_date')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.religious-special-days.create', [
            'categories' => ReligiousSpecialDay::CATEGORIES,
        ]);
    }

    public function store(StoreReligiousSpecialDayRequest $request): RedirectResponse
    {
        ReligiousSpecialDay::query()->create($this->payload($request->validated()));

        return to_route('admin.religious-special-days.index')->with('status', 'Dini özel gün oluşturuldu.');
    }

    public function edit(ReligiousSpecialDay $religiousSpecialDay): View
    {
        return view('admin.religious-special-days.edit', [
            'specialDay' => $religiousSpecialDay,
            'categories' => ReligiousSpecialDay::CATEGORIES,
        ]);
    }

    public function update(UpdateReligiousSpecialDayRequest $request, ReligiousSpecialDay $religiousSpecialDay): RedirectResponse
    {
        $religiousSpecialDay->update($this->payload($request->validated()));

        return to_route('admin.religious-special-days.index')->with('status', 'Dini özel gün güncellendi.');
    }

    public function destroy(ReligiousSpecialDay $religiousSpecialDay): RedirectResponse
    {
        $religiousSpecialDay->delete();

        return to_route('admin.religious-special-days.index')->with('status', 'Dini özel gün silindi.');
    }

    private function payload(array $validated): array
    {
        $recommendations = collect(preg_split('/\r\n|\r|\n/', $validated['recommendations_text']) ?: [])
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();

        unset($validated['recommendations_text']);
        $validated['recommendations'] = $recommendations;

        return $validated;
    }
}
