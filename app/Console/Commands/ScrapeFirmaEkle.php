<?php

namespace App\Console\Commands;

use App\Services\FirmaEkleScraperService;
use Illuminate\Console\Command;

class ScrapeFirmaEkle extends Command
{
    protected $signature = 'firmaekle:scrape
                            {--page=1 : Tek bir sayfa çek}
                            {--from=1 : Başlangıç sayfası}
                            {--to=1 : Bitiş sayfası}
                            {--all : Tüm sayfaları çek}';

    protected $description = 'FirmaEkle üzerinde ASP.NET postback pagination ile scraping yapar';

    public function handle(FirmaEkleScraperService $scraper): int
    {
        try {
            if ($this->option('all')) {
                $pages = $scraper->scrapeAll();
            } else {
                $from = (int) $this->option('from');
                $to = (int) $this->option('to');
                $page = (int) $this->option('page');

                if ($from !== 1 || $to !== 1) {
                    $pages = $scraper->scrapeAll($from, $to);
                } else {
                    $pages = [
                        $page => $scraper->scrapePage($page),
                    ];
                }
            }

            foreach ($pages as $pageNumber => $companies) {
                $this->info("Sayfa {$pageNumber} - ".count($companies).' kayıt');

                foreach ($companies as $index => $company) {
                    $this->line(sprintf(
                        '%d. %s | %s | %s | %s',
                        $index + 1,
                        $company['name'] ?? '-',
                        $company['phone'] ?? '-',
                        $company['email'] ?? '-',
                        $company['website'] ?? '-'
                    ));
                }

                $this->newLine();
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            report($e);

            return self::FAILURE;
        }
    }
}
