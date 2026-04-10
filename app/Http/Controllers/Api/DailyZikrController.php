<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyZikr;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyZikrController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
            'locale' => ['nullable', 'string', 'max:10'],
        ]);

        $date = Carbon::createFromFormat('Y-m-d', $validated['date'] ?? now()->toDateString());
        $locale = $validated['locale'] ?? null;

        $manualDaily = DailyZikr::query()
            ->whereDate('date', $date->toDateString())
            ->where('is_active', true)
            ->when($locale, function ($query, $locale): void {
                $query->where(function ($nested) use ($locale): void {
                    $nested->where('locale', $locale)->orWhereNull('locale');
                });
            })
            ->orderByRaw('CASE WHEN locale = ? THEN 0 ELSE 1 END', [$locale])
            ->with(['zikir.category'])
            ->first();

        if ($manualDaily) {
            $manualZikir = $manualDaily->zikir;

            if ($manualZikir && ! $manualZikir->category?->is_active) {
                $manualZikir = null;
            }

            return response()->json([
                'data' => [
                    'id' => (int) ($manualZikir?->id ?? $manualDaily->id),
                    'title' => (string) ($manualDaily->title ?: 'Bugunun zikri'),
                    'arabic' => (string) ($manualZikir?->zikir ?? ''),
                    'transliteration' => (string) ($manualDaily->transliteration ?: $manualZikir?->zikir ?: ''),
                    'meaning' => (string) ($manualDaily->meaning ?: $manualZikir?->anlami ?: ''),
                    'virtue_short' => (string) ($manualDaily->virtue_short ?: $manualZikir?->fazileti ?: ''),
                    'count_suggestion' => (int) ($manualDaily->count_suggestion ?: ($manualZikir && $manualZikir->hedef > 0 ? $manualZikir->hedef : 33)),
                    'share_text' => (string) ($manualDaily->share_text ?: sprintf('Bugunun zikri: %s (%d)', $manualZikir?->zikir ?: 'Zikir', (int) ($manualDaily->count_suggestion ?: ($manualZikir && $manualZikir->hedef > 0 ? $manualZikir->hedef : 33)))),
                    'date' => $date->toDateString(),
                ],
            ]);
        }

        return response()->json([
            'data' => null,
            'message' => 'No daily zikr selected for this date.',
        ]);
    }
}
