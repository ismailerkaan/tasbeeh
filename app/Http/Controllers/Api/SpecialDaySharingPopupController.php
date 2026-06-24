<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpecialDaySharingCampaign;
use Illuminate\Http\JsonResponse;

class SpecialDaySharingPopupController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $today = now('Europe/Istanbul')->toDateString();

        $data = SpecialDaySharingCampaign::query()
            ->with('images')
            ->where('is_active', true)
            ->whereDate('publish_date', $today)
            ->whereHas('images')
            ->orderBy('id')
            ->get()
            ->map(fn (SpecialDaySharingCampaign $campaign): array => [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'message' => $campaign->message,
                'publish_date' => $campaign->publish_date->format('Y-m-d'),
                'images' => $campaign->images->map(fn ($image): array => [
                    'id' => $image->id,
                    'url' => route('api.v1.special-day-sharing-images.show', $image, false),
                ])->values()->all(),
            ])
            ->values();

        return response()->json(['data' => $data]);
    }
}
