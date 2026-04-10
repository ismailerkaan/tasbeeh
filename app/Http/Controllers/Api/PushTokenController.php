<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePushTokenRequest;
use App\Models\DevicePushToken;
use Illuminate\Http\JsonResponse;

class PushTokenController extends Controller
{
    public function __invoke(StorePushTokenRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /** @var DevicePushToken $devicePushToken */
        $devicePushToken = DevicePushToken::query()->updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_identifier' => $validated['user_identifier'] ?? null,
                'platform' => $validated['platform'] ?? null,
                'is_active' => true,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Push token kaydedildi.',
            'id' => $devicePushToken->id,
        ]);
    }
}
