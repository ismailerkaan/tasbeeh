<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMobileFeedbackRequest;
use App\Models\MobileFeedback;
use Illuminate\Http\JsonResponse;

class StoreMobileFeedbackController extends Controller
{
    public function __invoke(StoreMobileFeedbackRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /** @var MobileFeedback $feedback */
        $feedback = MobileFeedback::query()->create([
            'user_identifier' => $validated['user_id'] ?? null,
            'full_name' => $validated['full_name'],
            'message' => $validated['message'],
            'fcm_token' => $validated['fcm_token'] ?? null,
            'platform' => $validated['platform'] ?? null,
            'device_model' => $validated['device_model'] ?? null,
            'os_version' => $validated['os_version'] ?? null,
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'status' => MobileFeedback::STATUS_NEW,
        ]);

        return response()->json([
            'message' => 'Geri bildiriminiz alindi. Tesekkur ederiz.',
            'id' => $feedback->id,
        ], 201);
    }
}
