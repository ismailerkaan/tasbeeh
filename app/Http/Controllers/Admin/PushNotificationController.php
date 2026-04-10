<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePushNotificationRequest;
use App\Http\Requests\Admin\UpdatePushNotificationRequest;
use App\Jobs\SendPushNotificationJob;
use App\Models\PushNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PushNotificationController extends Controller
{
    public function index(): View
    {
        return view('admin.push-notifications.index', [
            'notifications' => PushNotification::query()
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.push-notifications.create');
    }

    public function store(StorePushNotificationRequest $request): RedirectResponse
    {
        $pushNotification = PushNotification::query()->create(
            $request->normalizedPayload() + ['status' => PushNotification::STATUS_QUEUED]
        );

        SendPushNotificationJob::dispatch($pushNotification->id);

        return to_route('admin.push-notifications.index')
            ->with('status', 'Bildirim kuyruğa alındı ve gönderim başlatıldı.');
    }

    public function edit(PushNotification $pushNotification): View|RedirectResponse
    {
        if ($pushNotification->status !== PushNotification::STATUS_QUEUED) {
            return to_route('admin.push-notifications.index')
                ->with('status', 'Bu bildirim artık kuyrukta değil, düzenlenemez.');
        }

        return view('admin.push-notifications.edit', [
            'pushNotification' => $pushNotification,
        ]);
    }

    public function update(UpdatePushNotificationRequest $request, PushNotification $pushNotification): RedirectResponse
    {
        if ($pushNotification->status !== PushNotification::STATUS_QUEUED) {
            return to_route('admin.push-notifications.index')
                ->with('status', 'Sadece kuyruktaki bildirimler düzenlenebilir.');
        }

        $pushNotification->update($request->normalizedPayload());

        return to_route('admin.push-notifications.index')
            ->with('status', 'Kuyruktaki bildirim güncellendi.');
    }

    public function destroy(PushNotification $pushNotification): RedirectResponse
    {
        if ($pushNotification->status !== PushNotification::STATUS_QUEUED) {
            return to_route('admin.push-notifications.index')
                ->with('status', 'Sadece kuyruktaki bildirimler iptal edilebilir.');
        }

        $pushNotification->update([
            'status' => PushNotification::STATUS_CANCELED,
            'error_message' => 'Kullanıcı tarafından iptal edildi.',
            'sent_at' => now(),
        ]);

        return to_route('admin.push-notifications.index')
            ->with('status', 'Kuyruktaki bildirim iptal edildi.');
    }
}
