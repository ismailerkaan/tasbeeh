<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileUserDevice extends Model
{
    /** @use HasFactory<\Database\Factories\MobileUserDeviceFactory> */
    use HasFactory;

    protected $fillable = [
        'mobile_user_id',
        'fcm_token',
        'device_name',
        'device_model',
        'os',
        'os_version',
        'is_active',
        'last_seen_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mobile_user_id' => 'integer',
            'is_active' => 'boolean',
            'last_seen_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class, 'mobile_user_id');
    }
}
