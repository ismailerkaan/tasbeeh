<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentVersion;
use App\Models\DuaCategory;
use App\Models\HadisCategory;
use App\Models\ZikirCategory;
use Illuminate\Http\JsonResponse;

class ContentDataController extends Controller
{
    public function zikirs(): JsonResponse
    {
        $contentVersion = ContentVersion::current();

        $categories = ZikirCategory::query()
            ->where('is_active', true)
            ->with(['zikirs' => fn ($query) => $query->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'module' => 'zikir',
            'version' => $contentVersion->zikir_version,
            'updated_at' => $contentVersion->updated_at?->toIso8601String(),
            'data' => $categories->map(function (ZikirCategory $category): array {
                return [
                    'kategori_adi' => $category->name,
                    'kategori_aciklama' => (string) ($category->description ?? ''),
                    'zikirler' => $category->zikirs->map(function ($zikir): array {
                        return [
                            'id' => (int) $zikir->id,
                            'zikir' => (string) $zikir->zikir,
                            'anlami' => (string) $zikir->anlami,
                            'adet' => (int) $zikir->hedef,
                            'fazileti' => (string) $zikir->fazileti,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    public function duas(): JsonResponse
    {
        $contentVersion = ContentVersion::current();

        $categories = DuaCategory::query()
            ->where('is_active', true)
            ->with(['duas' => fn ($query) => $query->where('is_active', true)->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'module' => 'dua',
            'version' => $contentVersion->dua_version,
            'updated_at' => $contentVersion->updated_at?->toIso8601String(),
            'data' => $categories->map(function (DuaCategory $category): array {
                return [
                    'kategori' => $category->name,
                    'dualar' => $category->duas->map(function ($dua): array {
                        return [
                            'id' => (int) $dua->id,
                            'dua' => (string) $dua->dua,
                            'anlami' => (string) $dua->turkce_meali,
                            'kaynak' => (string) $dua->source,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    public function hadises(): JsonResponse
    {
        $contentVersion = ContentVersion::current();

        $categories = HadisCategory::query()
            ->where('is_active', true)
            ->with(['hadises' => fn ($query) => $query->where('is_active', true)->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'module' => 'hadis',
            'version' => $contentVersion->hadis_version,
            'updated_at' => $contentVersion->updated_at?->toIso8601String(),
            'data' => $categories->map(function (HadisCategory $category): array {
                return [
                    'kategori' => $category->name,
                    'hadisler' => $category->hadises->map(function ($hadis): array {
                        return [
                            'id' => (int) $hadis->id,
                            'hadis' => (string) $hadis->hadis,
                            'anlami' => (string) $hadis->turkce_meali,
                            'kaynak' => (string) $hadis->source,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }
}
