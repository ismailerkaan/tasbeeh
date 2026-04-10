<?php

namespace App\Console\Commands;

use App\Models\DuaCategory;
use App\Models\ZikirCategory;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

#[Signature('app:import-mobile-content {--fresh : Mevcut dua/zikir kayıtlarını temizleyip yeniden yükler}')]
#[Description('dua.js ve zikir.js dosyalarındaki kategori ve içerikleri veritabanına aktarır.')]
class ImportMobileContentCommand extends Command
{
    public function handle(): int
    {
        $duaPath = base_path('dua.js');
        $zikirPath = base_path('zikir.js');

        if (! file_exists($duaPath) || ! file_exists($zikirPath)) {
            $this->error('dua.js veya zikir.js dosyası bulunamadı.');

            return self::FAILURE;
        }

        $duaPayload = $this->decodeExportedJsonFile($duaPath);
        $zikirPayload = $this->decodeExportedJsonFile($zikirPath);

        if ((bool) $this->option('fresh')) {
            $this->truncateImportTables();
        }

        $this->importDuas($duaPayload);
        $this->importZikirs($zikirPayload);

        $this->info('İçerik içe aktarma tamamlandı.');
        $this->line('Dua kategori sayısı: '.DuaCategory::query()->count());
        $this->line('Zikir kategori sayısı: '.ZikirCategory::query()->count());
        $this->line('Dua sayısı: '.DB::table('duas')->count());
        $this->line('Zikir sayısı: '.DB::table('zikirs')->count());

        return self::SUCCESS;
    }

    private function truncateImportTables(): void
    {
        DB::table('duas')->delete();
        DB::table('zikirs')->delete();
        DB::table('dua_categories')->delete();
        DB::table('zikir_categories')->delete();
    }

    /**
     * @param array<int, array<string, mixed>> $duaPayload
     */
    private function importDuas(array $duaPayload): void
    {
        foreach ($duaPayload as $group) {
            $categoryName = trim((string) ($group['kategori'] ?? ''));

            if ($categoryName === '') {
                continue;
            }

            $category = DuaCategory::query()->updateOrCreate(
                ['name' => $categoryName],
                ['is_active' => true]
            );

            $dualar = $group['dualar'] ?? [];

            if (! is_array($dualar)) {
                continue;
            }

            foreach ($dualar as $duaItem) {
                if (! is_array($duaItem)) {
                    continue;
                }

                $id = (int) ($duaItem['id'] ?? 0);

                if ($id <= 0) {
                    continue;
                }

                DB::table('duas')->updateOrInsert(
                    ['id' => $id],
                    [
                        'dua_category_id' => $category->id,
                        'source' => (string) ($duaItem['kaynak'] ?? ''),
                        'dua' => (string) ($duaItem['dua'] ?? ''),
                        'turkce_meali' => (string) ($duaItem['anlami'] ?? ''),
                        'is_active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * @param array<int, array<string, mixed>> $zikirPayload
     */
    private function importZikirs(array $zikirPayload): void
    {
        foreach ($zikirPayload as $group) {
            $categoryName = trim((string) ($group['kategori_adi'] ?? ''));

            if ($categoryName === '') {
                continue;
            }

            $category = ZikirCategory::query()->updateOrCreate(
                ['name' => $categoryName],
                [
                    'description' => (string) ($group['kategori_aciklama'] ?? ''),
                    'is_active' => true,
                ]
            );

            $zikirler = $group['zikirler'] ?? [];

            if (! is_array($zikirler)) {
                continue;
            }

            foreach ($zikirler as $zikirItem) {
                if (! is_array($zikirItem)) {
                    continue;
                }

                $id = (int) ($zikirItem['id'] ?? 0);

                if ($id <= 0) {
                    continue;
                }

                DB::table('zikirs')->updateOrInsert(
                    ['id' => $id],
                    [
                        'zikir_category_id' => $category->id,
                        'zikir' => (string) ($zikirItem['zikir'] ?? ''),
                        'anlami' => (string) ($zikirItem['anlami'] ?? ''),
                        'fazileti' => (string) ($zikirItem['fazileti'] ?? ''),
                        'hedef' => max(1, (int) ($zikirItem['adet'] ?? 1)),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function decodeExportedJsonFile(string $path): array
    {
        $rawContent = file_get_contents($path);

        if (! is_string($rawContent) || trim($rawContent) === '') {
            throw new RuntimeException("Dosya boş veya okunamadı: {$path}");
        }

        $withoutPrefix = preg_replace('/^\s*export\s+default\s+/u', '', $rawContent);
        $json = is_string($withoutPrefix) ? rtrim(trim($withoutPrefix), ";\n\r\t ") : '';

        /** @var mixed $decoded */
        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            throw new RuntimeException("JSON parse edilemedi: {$path}");
        }

        return $decoded;
    }
}
