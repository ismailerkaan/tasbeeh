<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PrayerTimesController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:d-m-Y'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'method' => ['nullable', 'integer', 'min:0'],
        ]);

        $date = $validated['date'] ?? now()->format('d-m-Y');

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->get("https://api.aladhan.com/v1/timingsByCity/{$date}", [
                    'city' => $validated['city'],
                    'country' => $validated['country'] ?? 'turkey',
                    'method' => $validated['method'] ?? 13,
                ]);
        } catch (ConnectionException) {
            return response()->json([
                'message' => 'Prayer times provider is unavailable.',
            ], 503);
        }

        if (! $response->successful()) {
            return response()->json([
                'message' => 'Prayer times could not be fetched.',
            ], 502);
        }

        return response()->json($response->json(), $response->status());
    }
}
