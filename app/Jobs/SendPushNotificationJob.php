<?php

namespace App\Jobs;

use App\Models\DevicePushToken;
use App\Models\PushNotification;
use App\Services\Push\FirebasePushService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $pushNotificationId)
    {
    }

    public function handle(FirebasePushService $firebasePushService): void
    {
        $pushNotification = PushNotification::query()->find($this->pushNotificationId);

        if (! $pushNotification instanceof PushNotification) {
            return;
        }

        if ($pushNotification->status !== PushNotification::STATUS_QUEUED) {
            return;
        }

        $tokenQuery = DevicePushToken::query()
            ->where('is_active', true);

        if ($pushNotification->target_type === PushNotification::TARGET_USER) {
            $tokenQuery->where('user_identifier', $pushNotification->target_user_identifier);
        }

        $successCount = 0;
        $failedCount = 0;
        $lastError = null;

        $totalTokens = (clone $tokenQuery)->count();

        if ($totalTokens === 0) {
            $pushNotification->update([
                'status' => PushNotification::STATUS_FAILED,
                'error_message' => 'Hedef için aktif push token bulunamadı.',
                'sent_at' => now(),
            ]);

            return;
        }

        $tokenQuery->chunkById(200, function ($tokens) use (
            $firebasePushService,
            $pushNotification,
            &$successCount,
            &$failedCount,
            &$lastError
        ): void {
            foreach ($tokens as $token) {
                $result = $firebasePushService->sendToToken(
                    token: $token->token,
                    title: $pushNotification->title,
                    body: $pushNotification->body,
                    data: $pushNotification->data ?? [],
                );

                if (($result['success'] ?? false) === true) {
                    $successCount++;

                    continue;
                }

                $failedCount++;
                $lastError = is_string($result['error'] ?? null) ? $result['error'] : 'Unknown error';

                if (in_array($lastError, ['NotRegistered', 'InvalidRegistration'], true)) {
                    $token->update(['is_active' => false]);
                }
            }
        });

        $pushNotification->update([
            'status' => $successCount > 0 ? PushNotification::STATUS_SENT : PushNotification::STATUS_FAILED,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'error_message' => $lastError,
            'sent_at' => now(),
        ]);
    }
}
