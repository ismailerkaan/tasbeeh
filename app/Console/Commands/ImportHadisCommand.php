<?php

namespace App\Console\Commands;

use App\Models\Hadis;
use App\Models\HadisCategory;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

#[Signature('app:import-hadis {--fresh : Mevcut hadis kayıtlarını temizleyip yeniden yükler}')]
#[Description('hadis.json dosyasındaki kategori ve hadisleri veritabanına aktarır.')]
class ImportHadisCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = base_path('hadis.json');

        if (! file_exists($path)) {
            $this->error('hadis.json dosyası bulunamadı.');

            return self::FAILURE;
        }

        try {
            $payload = $this->decodeJsonFile($path);
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if ((bool) $this->option('fresh')) {
            $this->truncateTables();
            $this->info('Mevcut hadis ve kategori verileri silindi.');
        }

        $this->importHadises($payload);

        $this->info('Hadis içe aktarma tamamlandı.');
        $this->line('Kategori sayısı: '.HadisCategory::query()->count());
        $this->line('Hadis sayısı: '.Hadis::query()->count());

        return self::SUCCESS;
    }

    private function truncateTables(): void
    {
        DB::table('hadises')->delete();
        DB::table('hadis_categories')->delete();
    }

    /**
     * @param  array<int, array<string, mixed>>  $payload
     */
    private function importHadises(array $payload): void
    {
        $categoriesSortOrder = 0;

        foreach ($payload as $item) {
            $categoryName = trim((string) ($item['kategori'] ?? ''));

            if ($categoryName === '') {
                continue;
            }

            /** @var HadisCategory|null $category */
            $category = HadisCategory::query()->where('name', $categoryName)->first();

            if ($category === null) {
                $categoriesSortOrder++;
                /** @var HadisCategory $category */
                $category = HadisCategory::query()->create([
                    'name' => $categoryName,
                    'sort_order' => $categoriesSortOrder,
                    'is_active' => true,
                ]);
            }

            $source = trim((string) ($item['kaynagi'] ?? ''));
            $hadisText = trim((string) ($item['hadis'] ?? ''));
            $translation = trim((string) ($item['turkce_meali'] ?? ''));
            $isActive = (bool) ($item['aktif'] ?? true);

            $exists = Hadis::query()
                ->where('hadis_category_id', $category->id)
                ->where('hadis', $hadisText)
                ->exists();

            if (! $exists) {
                Hadis::query()->create([
                    'hadis_category_id' => $category->id,
                    'source' => $source,
                    'hadis' => $hadisText,
                    'turkce_meali' => $translation,
                    'is_active' => $isActive,
                ]);
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function decodeJsonFile(string $path): array
    {
        $rawContent = file_get_contents($path);

        if ($rawContent === false || trim($rawContent) === '') {
            throw new RuntimeException("Dosya boş veya okunamadı: {$path}");
        }

        /** @var mixed $decoded */
        $decoded = json_decode($rawContent, true);

        if (! is_array($decoded)) {
            throw new RuntimeException("JSON parse edilemedi: {$path}");
        }

        return $decoded;
    }
}
