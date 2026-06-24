<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaabaLiveStream extends Model
{
    protected $fillable = [
        'title',
        'youtube_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function youtubeVideoId(): ?string
    {
        $url = trim($this->youtube_url);
        $patterns = [
            '~youtu\.be/([A-Za-z0-9_-]{6,})~',
            '~youtube\.com/(?:live|embed|shorts)/([A-Za-z0-9_-]{6,})~',
            '~youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{6,})~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }
}
