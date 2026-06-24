<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateKaabaLiveStreamRequest;
use App\Models\KaabaLiveStream;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KaabaLiveStreamController extends Controller
{
    public function edit(): View
    {
        return view('admin.kaaba-live-stream.edit', [
            'liveStream' => KaabaLiveStream::query()->first(),
        ]);
    }

    public function update(UpdateKaabaLiveStreamRequest $request): RedirectResponse
    {
        KaabaLiveStream::query()->updateOrCreate(['id' => 1], $request->validated());

        return to_route('admin.kaaba-live-stream.edit')
            ->with('status', 'Kâbe canlı yayın bağlantısı güncellendi.');
    }
}
