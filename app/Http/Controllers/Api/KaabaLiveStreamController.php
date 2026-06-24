<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KaabaLiveStream;
use Illuminate\Http\JsonResponse;

class KaabaLiveStreamController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $liveStream = KaabaLiveStream::query()->where('is_active', true)->first();
        $videoId = $liveStream?->youtubeVideoId();

        return response()->json([
            'data' => $liveStream && $videoId ? [
                'title' => $liveStream->title,
                'youtube_url' => $liveStream->youtube_url,
                'video_id' => $videoId,
                'embed_url' => "https://www.youtube.com/embed/{$videoId}",
            ] : null,
        ]);
    }
}
