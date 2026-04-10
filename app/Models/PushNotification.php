<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    /** @use HasFactory<\Database\Factories\PushNotificationFactory> */
    use HasFactory;

    public const TARGET_ALL = 'all';
    public const TARGET_USER = 'user';

    public const STATUS_QUEUED = 'queued';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELED = 'canceled';

    /**
     * @var list<string>
     */
    public const TARGET_TYPES = [
        self::TARGET_ALL,
        self::TARGET_USER,
    ];

    protected $fillable = [
        'title',
        'body',
        'target_type',
        'target_user_identifier',
        'data',
        'status',
        'success_count',
        'failed_count',
        'error_message',
        'sent_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'success_count' => 'integer',
            'failed_count' => 'integer',
            'sent_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
