<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicePushToken extends Model
{
    /** @use HasFactory<\Database\Factories\DevicePushTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'token',
        'user_identifier',
        'platform',
        'is_active',
        'last_seen_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_seen_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
