<?php

namespace App\Services\Push;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class FirebasePushService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{success: bool, error: string|null}
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        try {
            $credentials = $this->loadServiceAccountCredentials();
            $accessToken = $this->resolveAccessToken($credentials);
            $endpoint = sprintf(
                (string) config('push.firebase.send_endpoint_format'),
                $credentials['project_id']
            );
            $payloadData = $this->stringifyData($data);
            $messagePayload = [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => [
                    'priority' => 'HIGH',
                ],
            ];

            if ($payloadData !== []) {
                $messagePayload['data'] = $payloadData;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$accessToken,
                'Content-Type' => 'application/json',
            ])->post((string) $endpoint, [
                'message' => $messagePayload,
            ]);

            if (! $response->successful()) {
                /** @var array<string, mixed> $json */
                $json = $response->json() ?? [];
                $error = data_get($json, 'error.message');

                return [
                    'success' => false,
                    'error' => is_string($error) ? $error : 'HTTP '.$response->status(),
                ];
            }

            return [
                'success' => true,
                'error' => null,
            ];
        } catch (Throwable $throwable) {
            return [
                'success' => false,
                'error' => $throwable->getMessage(),
            ];
        }
    }

    /**
     * @return array{project_id: string, client_email: string, private_key: string, token_uri: string}
     */
    private function loadServiceAccountCredentials(): array
    {
        $serviceAccountPath = config('push.firebase.service_account_path');

        if (! is_string($serviceAccountPath) || trim($serviceAccountPath) === '') {
            throw new RuntimeException('FCM_SERVICE_ACCOUNT_PATH is missing. Please set it in .env.');
        }

        if (! file_exists($serviceAccountPath)) {
            throw new RuntimeException("Firebase service account file not found: {$serviceAccountPath}");
        }

        $raw = file_get_contents($serviceAccountPath);

        if (! is_string($raw) || trim($raw) === '') {
            throw new RuntimeException('Firebase service account file is empty.');
        }

        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('Firebase service account file is not valid JSON.');
        }

        $requiredKeys = ['project_id', 'client_email', 'private_key', 'token_uri'];

        foreach ($requiredKeys as $requiredKey) {
            if (! isset($decoded[$requiredKey]) || ! is_string($decoded[$requiredKey]) || trim($decoded[$requiredKey]) === '') {
                throw new RuntimeException("Firebase service account JSON missing [{$requiredKey}] field.");
            }
        }

        return [
            'project_id' => $decoded['project_id'],
            'client_email' => $decoded['client_email'],
            'private_key' => $decoded['private_key'],
            'token_uri' => $decoded['token_uri'],
        ];
    }

    /**
     * @param  array{project_id: string, client_email: string, private_key: string, token_uri: string}  $credentials
     */
    private function resolveAccessToken(array $credentials): string
    {
        $cacheKey = 'push:fcm:access-token:'.$credentials['project_id'];
        $cachedToken = Cache::get($cacheKey);

        if (is_string($cachedToken) && trim($cachedToken) !== '') {
            return $cachedToken;
        }

        $assertion = $this->buildServiceAccountAssertion($credentials);

        $response = Http::asForm()->post($credentials['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to fetch Firebase access token: HTTP '.$response->status());
        }

        /** @var array<string, mixed> $json */
        $json = $response->json() ?? [];
        $accessToken = $json['access_token'] ?? null;

        if (! is_string($accessToken) || trim($accessToken) === '') {
            throw new RuntimeException('Firebase access token response missing access_token.');
        }

        $expiresIn = (int) ($json['expires_in'] ?? 3600);
        $ttlSeconds = max(60, $expiresIn - 60);
        Cache::put($cacheKey, $accessToken, now()->addSeconds($ttlSeconds));

        return $accessToken;
    }

    /**
     * @param  array{project_id: string, client_email: string, private_key: string, token_uri: string}  $credentials
     */
    private function buildServiceAccountAssertion(array $credentials): string
    {
        $now = time();

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => $credentials['client_email'],
            'sub' => $credentials['client_email'],
            'aud' => $credentials['token_uri'],
            'scope' => (string) config('push.firebase.scope'),
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $base64Header = $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR));
        $base64Payload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        $unsignedToken = $base64Header.'.'.$base64Payload;

        $privateKeyResource = openssl_pkey_get_private($credentials['private_key']);

        if ($privateKeyResource === false) {
            throw new RuntimeException('Invalid private key in Firebase service account JSON.');
        }

        $signature = '';
        $signed = openssl_sign($unsignedToken, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKeyResource);

        if (! $signed) {
            throw new RuntimeException('Unable to sign Firebase JWT assertion.');
        }

        return $unsignedToken.'.'.$this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    private function stringifyData(array $data): array
    {
        $output = [];

        foreach ($data as $key => $value) {
            if (! is_string($key) || trim($key) === '') {
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $output[$key] = Str::of((string) $value)->toString();

                continue;
            }

            $output[$key] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
        }

        return $output;
    }
}
