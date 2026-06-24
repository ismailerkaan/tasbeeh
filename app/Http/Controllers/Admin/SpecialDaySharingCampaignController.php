<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSpecialDaySharingCampaignRequest;
use App\Http\Requests\Admin\UpdateSpecialDaySharingCampaignRequest;
use App\Models\SpecialDaySharingCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SpecialDaySharingCampaignController extends Controller
{
    public function index(): View
    {
        return view('admin.special-day-sharing-campaigns.index', [
            'campaigns' => SpecialDaySharingCampaign::query()
                ->withCount('images')
                ->orderByDesc('publish_date')
                ->orderByDesc('id')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.special-day-sharing-campaigns.create');
    }

    public function store(StoreSpecialDaySharingCampaignRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $validated = $request->validated();
            $campaign = SpecialDaySharingCampaign::query()->create($this->payload($validated));
            $this->storeImages($campaign, $validated['images']);
        });

        return to_route('admin.special-day-sharing-campaigns.index')->with('status', 'Özel gün paylaşımı oluşturuldu.');
    }

    public function edit(SpecialDaySharingCampaign $specialDaySharingCampaign): View
    {
        $specialDaySharingCampaign->load('images');

        return view('admin.special-day-sharing-campaigns.edit', ['campaign' => $specialDaySharingCampaign]);
    }

    public function update(UpdateSpecialDaySharingCampaignRequest $request, SpecialDaySharingCampaign $specialDaySharingCampaign): RedirectResponse
    {
        $validated = $request->validated();
        $removeIds = $specialDaySharingCampaign->images()
            ->whereIn('id', $validated['remove_image_ids'] ?? [])
            ->pluck('id');
        $newImageCount = count($validated['images'] ?? []);

        if ($specialDaySharingCampaign->images()->count() - $removeIds->count() + $newImageCount < 1) {
            throw ValidationException::withMessages(['images' => 'Kampanyada en az bir görsel bulunmalıdır.']);
        }

        DB::transaction(function () use ($validated, $removeIds, $specialDaySharingCampaign): void {
            $specialDaySharingCampaign->update($this->payload($validated));

            foreach ($specialDaySharingCampaign->images()->whereIn('id', $removeIds)->get() as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            $this->storeImages($specialDaySharingCampaign, $validated['images'] ?? []);
        });

        return to_route('admin.special-day-sharing-campaigns.index')->with('status', 'Özel gün paylaşımı güncellendi.');
    }

    public function destroy(SpecialDaySharingCampaign $specialDaySharingCampaign): RedirectResponse
    {
        foreach ($specialDaySharingCampaign->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $specialDaySharingCampaign->delete();

        return to_route('admin.special-day-sharing-campaigns.index')->with('status', 'Özel gün paylaşımı silindi.');
    }

    private function payload(array $validated): array
    {
        unset($validated['images'], $validated['remove_image_ids']);

        return $validated;
    }

    /** @param array<int, UploadedFile> $images */
    private function storeImages(SpecialDaySharingCampaign $campaign, array $images): void
    {
        $sortOrder = (int) $campaign->images()->max('sort_order');

        foreach ($images as $image) {
            $campaign->images()->create([
                'path' => $image->store('special-day-sharing', 'public'),
                'original_name' => $image->getClientOriginalName(),
                'sort_order' => ++$sortOrder,
            ]);
        }
    }
}
