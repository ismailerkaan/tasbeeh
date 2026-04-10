<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentCheckController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'zikir_version' => ['nullable', 'integer', 'min:1'],
            'dua_version' => ['nullable', 'integer', 'min:1'],
            'prayer_times_version' => ['nullable', 'integer', 'min:1'],
        ]);

        $contentVersion = ContentVersion::current();
        $serverVersions = [
            'zikir_version' => $contentVersion->zikir_version,
            'dua_version' => $contentVersion->dua_version,
            'prayer_times_version' => $contentVersion->prayer_times_version,
        ];

        $changedModules = collect(ContentVersion::MODULE_COLUMN_MAP)
            ->filter(function (string $column, string $module) use ($validated, $serverVersions): bool {
                $clientVersion = $validated[$column] ?? 0;

                return (int) $clientVersion < (int) $serverVersions[$column];
            })
            ->keys()
            ->values();

        return response()->json([
            'versions' => $serverVersions,
            'has_updates' => $changedModules->isNotEmpty(),
            'changed_modules' => $changedModules,
            'checked_at' => now()->toIso8601String(),
            'updated_at' => $contentVersion->updated_at?->toIso8601String(),
        ]);
    }
}
