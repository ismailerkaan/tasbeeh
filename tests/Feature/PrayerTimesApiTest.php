<?php

use Illuminate\Support\Facades\Http;

test('prayer times endpoint proxies provider response', function () {
    Http::fake([
        'api.aladhan.com/*' => Http::response([
            'code' => 200,
            'status' => 'OK',
            'data' => [
                'timings' => [
                    'Fajr' => '04:10',
                    'Dhuhr' => '13:08',
                    'Asr' => '17:02',
                    'Maghrib' => '20:42',
                    'Isha' => '22:18',
                ],
            ],
        ]),
    ]);

    $this->getJson('/api/v1/prayer-times?date=24-06-2026&city=istanbul&country=turkey&method=13')
        ->assertOk()
        ->assertJsonPath('data.timings.Fajr', '04:10')
        ->assertJsonPath('data.timings.Isha', '22:18');

    Http::assertSent(fn ($request): bool =>
        $request->url() === 'https://api.aladhan.com/v1/timingsByCity/24-06-2026?city=istanbul&country=turkey&method=13'
    );
});

test('prayer times endpoint validates city', function () {
    $this->getJson('/api/v1/prayer-times')->assertUnprocessable()->assertJsonValidationErrors('city');
});
