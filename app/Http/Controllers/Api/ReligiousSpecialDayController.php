<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReligiousSpecialDay;
use Illuminate\Http\JsonResponse;

class ReligiousSpecialDayController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $data = ReligiousSpecialDay::query()
            ->where('is_active', true)
            ->orderBy('event_date')
            ->get()
            ->map(function (ReligiousSpecialDay $day): array {
                $category = ReligiousSpecialDay::CATEGORIES[$day->category]
                    ?? ReligiousSpecialDay::CATEGORIES['mubarek_gunler_aylar'];

                return [
                    'id' => $day->id,
                    'title' => $day->title,
                    'category' => $day->category,
                    'category_label' => $category['label'],
                    'category_color' => $category['color'],
                    'event_date' => $day->event_date->format('Y-m-d'),
                    'hijri_date' => $day->hijri_date,
                    'short_description' => $day->short_description,
                    'description' => $day->description,
                    'recommendations' => array_values($day->recommendations ?? []),
                ];
            })
            ->values();

        return response()->json(['data' => $data]);
    }
}
