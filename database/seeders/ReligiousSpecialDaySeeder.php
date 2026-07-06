<?php

namespace Database\Seeders;

use App\Models\ReligiousSpecialDay;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ReligiousSpecialDaySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('ozelgun.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("ozelgun.json not found at: {$jsonPath}");
            return;
        }

        $json = File::get($jsonPath);
        $data = json_decode($json, true);

        if (!$data) {
            $this->command->error("Invalid JSON data in ozelgun.json");
            return;
        }

        $categoryMap = [
            'kandil geceleri' => 'kandil_geceleri',
            'dini bayramlar' => 'dini_bayramlar',
            'mübarek aylar & günler' => 'mubarek_gunler_aylar',
            'mübarek günler & aylar' => 'mubarek_gunler_aylar',
        ];

        foreach ($data as $item) {
            $categoryKey = mb_strtolower(trim($item['kategori']), 'UTF-8');
            $category = $categoryMap[$categoryKey] ?? 'mubarek_gunler_aylar';

            $eventDate = Carbon::createFromFormat('d.m.Y', $item['tarih'])->format('Y-m-d');

            ReligiousSpecialDay::updateOrCreate(
                [
                    'title' => $item['gunun_adi'],
                    'event_date' => $eventDate,
                ],
                [
                    'category' => $category,
                    'hijri_date' => $item['hicri_tarih'] ?? null,
                    'short_description' => $item['kisa_aciklama'] ?? null,
                    'description' => $item['gunun_anlami_ve_onemi'] ?? '',
                    'recommendations' => $item['o_gun_yapilmasi_onerilenler'] ?? [],
                    'is_active' => $item['mobil_uygulamada_goster'] ?? true,
                ]
            );
        }

        $this->command->info('Religious special days imported successfully.');
    }
}
