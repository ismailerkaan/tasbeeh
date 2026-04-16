<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Exception;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FirmaEkleScraperService
{
    protected string $baseUrl = 'https://www.firmaekle.com/';

    protected string $searchUrl = 'https://www.firmaekle.com/?act=secfirm&word=+Web+tasar%C4%B1m&il=-111&ilce=-111&cat=-111';

    protected CookieJar $cookieJar;

    protected array $state = [];

    public function __construct()
    {
        $this->cookieJar = new CookieJar;
    }

    /**
     * Tüm sayfaları gezer
     */
    public function scrapeAll(int $startPage = 1, ?int $endPage = null): array
    {
        $firstHtml = $this->loadInitialPage();
        $this->state = $this->extractAspNetState($firstHtml);

        $results = [];
        $currentPage = $startPage;

        if ($startPage === 1) {
            $results[1] = $this->parseCompaniesFromHtml($firstHtml);
            $maxPage = $this->detectMaxPage($firstHtml);
        } else {
            $maxPage = null;
        }

        if ($endPage === null) {
            $endPage = $maxPage ?? 1;
        }

        if ($startPage > 1) {
            for ($page = 2; $page <= $startPage; $page++) {
                $delta = $this->goToPage($page);
                $html = $this->extractHtmlFromDelta($delta);

                $this->refreshStateFromDelta($delta);

                if ($page === $startPage) {
                    $results[$page] = $this->parseCompaniesFromHtml($html);
                    if ($maxPage === null) {
                        $maxPage = $this->detectMaxPage($html);
                        if ($endPage === null) {
                            $endPage = $maxPage;
                        }
                    }
                }
            }
            $currentPage = $startPage;
        }

        for ($page = max(2, $currentPage + 1); $page <= $endPage; $page++) {
            $delta = $this->goToPage($page);
            $html = $this->extractHtmlFromDelta($delta);

            $this->refreshStateFromDelta($delta);
            $results[$page] = $this->parseCompaniesFromHtml($html);
        }

        return $results;
    }

    /**
     * Tek sayfa çekmek için
     */
    public function scrapePage(int $page = 1): array
    {
        if ($page < 1) {
            throw new Exception('Sayfa numarası 1 veya daha büyük olmalı.');
        }

        $firstHtml = $this->loadInitialPage();
        $this->state = $this->extractAspNetState($firstHtml);

        if ($page === 1) {
            return $this->parseCompaniesFromHtml($firstHtml);
        }

        $delta = null;
        for ($i = 2; $i <= $page; $i++) {
            $delta = $this->goToPage($i);
            $this->refreshStateFromDelta($delta);
        }

        if ($delta === null) {
            return [];
        }

        $html = $this->extractHtmlFromDelta($delta);

        return $this->parseCompaniesFromHtml($html);
    }

    protected function loadInitialPage(): string
    {
        $response = Http::withOptions([
            'cookies' => $this->cookieJar,
            'verify' => true,
            'http_errors' => false,
        ])
            ->withHeaders($this->defaultHeaders(false))
            ->get($this->searchUrl);

        if (! $response->successful()) {
            throw new Exception('İlk sayfa alınamadı. HTTP: '.$response->status());
        }

        return $response->body();
    }

    protected function goToPage(int $page): string
    {
        $payload = [
            'ctl00$ScriptManager1' => 'ctl00$ContentPlaceHolder2$ctl00$up|ctl00$ContentPlaceHolder2$ctl00$grid',
            'ctl00$findMotor1$txtAranacak' => '',
            'ctl00$findMotor1$dropIlMotor' => '-111',
            'ctl00$findMotor1$dropIlceMotor' => '-111',
            'ctl00$findMotor1$dropSector' => '-111',
            '__EVENTTARGET' => 'ctl00$ContentPlaceHolder2$ctl00$grid',
            '__EVENTARGUMENT' => 'Page$'.$page,
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => $this->state['__VIEWSTATE'] ?? '',
            '__VIEWSTATEGENERATOR' => $this->state['__VIEWSTATEGENERATOR'] ?? '',
            '__ASYNCPOST' => 'true',
        ];

        if (! empty($this->state['__EVENTVALIDATION'])) {
            $payload['__EVENTVALIDATION'] = $this->state['__EVENTVALIDATION'];
        }

        $response = Http::asForm()
            ->withOptions([
                'cookies' => $this->cookieJar,
                'verify' => true,
                'http_errors' => false,
            ])
            ->withHeaders($this->defaultHeaders(true))
            ->post($this->searchUrl, $payload);

        if (! $response->successful()) {
            throw new Exception("Sayfa {$page} alınamadı. HTTP: ".$response->status());
        }

        return $response->body();
    }

    protected function defaultHeaders(bool $isAjax = false): array
    {
        $headers = [
            'Accept' => '*/*',
            'Accept-Language' => 'tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
            'Cache-Control' => 'no-cache',
            'Pragma' => 'no-cache',
            'Origin' => 'https://www.firmaekle.com',
            'Referer' => $this->searchUrl,
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
        ];

        if ($isAjax) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
            $headers['X-MicrosoftAjax'] = 'Delta=true';
            $headers['X-Requested-With'] = 'XMLHttpRequest';
        }

        return $headers;
    }

    protected function extractAspNetState(string $html): array
    {
        return [
            '__VIEWSTATE' => $this->extractHiddenInput($html, '__VIEWSTATE'),
            '__VIEWSTATEGENERATOR' => $this->extractHiddenInput($html, '__VIEWSTATEGENERATOR'),
            '__EVENTVALIDATION' => $this->extractHiddenInput($html, '__EVENTVALIDATION'),
        ];
    }

    protected function extractHiddenInput(string $html, string $name): ?string
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);
        $query = sprintf("//input[@type='hidden' and @name='%s']", $name);
        $node = $xpath->query($query)?->item(0);

        return $node?->getAttribute('value');
    }

    /**
     * ASP.NET delta response içinden güncel hidden alanları çeker
     */
    protected function refreshStateFromDelta(string $delta): void
    {
        foreach (['__VIEWSTATE', '__VIEWSTATEGENERATOR', '__EVENTVALIDATION'] as $field) {
            $value = $this->extractDeltaHiddenField($delta, $field);
            if ($value !== null) {
                $this->state[$field] = $value;
            }
        }
    }

    /**
     * Delta cevabında gizli alanlar pipe formatında gelebilir
     */
    protected function extractDeltaHiddenField(string $delta, string $field): ?string
    {
        $patterns = [
            '/hiddenField\|'.preg_quote($field, '/').'\|(.*?)\|/s',
            '/\b'.preg_quote($field, '/').'\|(.*?)\|/s',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $delta, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Delta içinden HTML parçayı ayıklar
     */
    protected function extractHtmlFromDelta(string $delta): string
    {
        $possibleTargets = [
            'ctl00_ContentPlaceHolder2_ctl00_up',
            'ctl00$ContentPlaceHolder2$ctl00$up',
        ];

        foreach ($possibleTargets as $target) {
            $pattern = '/updatePanel\|'.preg_quote($target, '/').'\|(.*?)(?:\|hiddenField\||\|scriptBlock\||\|pageRedirect\||$)/s';
            if (preg_match($pattern, $delta, $matches)) {
                return $matches[1];
            }
        }

        return $delta;
    }

    /**
     * Sayfadaki firma kartlarını parse eder
     * HTML yapısı çok temiz olmadığı için name/url/mail/tel odaklı gidiyoruz
     */
    protected function parseCompaniesFromHtml(string $html): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);

        $xpath = new DOMXPath($dom);

        $companyLinks = $xpath->query("//a[contains(@href, 'firmDetails') or contains(@href, 'firmDetay') or contains(@href, 'firmDetails')]");

        $results = [];
        $seen = [];

        foreach ($companyLinks as $link) {
            $name = trim($link->textContent);
            $detailUrl = trim($link->getAttribute('href'));

            if ($name === '') {
                continue;
            }

            $normalizedKey = md5($name.'|'.$detailUrl);
            if (isset($seen[$normalizedKey])) {
                continue;
            }
            $seen[$normalizedKey] = true;

            $rowHtml = $dom->saveHTML($link->parentNode?->parentNode?->parentNode ?? $link);

            $results[] = [
                'name' => $name,
                'detail_url' => $this->normalizeUrl($detailUrl),
                'website' => $this->extractFirstUrl($rowHtml),
                'email' => $this->extractFirstEmail($rowHtml),
                'phone' => $this->extractFirstPhone($rowHtml),
                'description' => $this->extractDescription($rowHtml, $name),
            ];
        }

        return array_values($results);
    }

    protected function normalizeUrl(string $url): ?string
    {
        if ($url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        if (Str::startsWith($url, '~/')) {
            return $this->baseUrl.ltrim(Str::after($url, '~/'), '/');
        }

        return $this->baseUrl.ltrim($url, '/');
    }

    protected function extractFirstUrl(string $html): ?string
    {
        if (preg_match('/https?:\/\/[^\s"<]+/i', html_entity_decode($html), $matches)) {
            return $matches[0];
        }

        return null;
    }

    protected function extractFirstEmail(string $html): ?string
    {
        if (preg_match('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', html_entity_decode($html), $matches)) {
            return $matches[0];
        }

        return null;
    }

    protected function extractFirstPhone(string $html): ?string
    {
        $text = html_entity_decode(strip_tags($html));
        if (preg_match('/(\+?\d[\d\(\)\s\-]{8,}\d)/', $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    protected function extractDescription(string $html, string $name): ?string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode($html))));
        $text = str_replace($name, '', $text);
        $text = trim($text);

        return $text !== '' ? Str::limit($text, 500, '') : null;
    }

    protected function detectMaxPage(string $html): int
    {
        $text = html_entity_decode(strip_tags($html));

        if (preg_match('/Toplam\s*:\s*(\d+)\s*sonuç/i', $text, $matches)) {
            $total = (int) $matches[1];

            return (int) ceil($total / 15);
        }

        preg_match_all('/Page\$(\d+)/', $html, $matches);
        if (! empty($matches[1])) {
            return max(array_map('intval', $matches[1]));
        }

        return 1;
    }
}
