<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MobileUser extends Model
{
    /** @use HasFactory<\Database\Factories\MobileUserFactory> */
    use HasFactory;

    protected $fillable = [
        'external_user_id',
        'city',
        'district',
        'is_opt_in',
        'total_zikir_count',
        'current_streak',
        'best_streak',
        'total_active_days',
        'last_active_date',
        'daily_activity_summary',
        'zikir_version',
        'dua_version',
        'prayer_times_version',
        'synced_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_opt_in' => 'boolean',
            'total_zikir_count' => 'integer',
            'current_streak' => 'integer',
            'best_streak' => 'integer',
            'total_active_days' => 'integer',
            'last_active_date' => 'date',
            'daily_activity_summary' => 'array',
            'zikir_version' => 'integer',
            'dua_version' => 'integer',
            'prayer_times_version' => 'integer',
            'synced_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function devices(): HasMany
    {
        return $this->hasMany(MobileUserDevice::class);
    }

    public function lastZikir(): HasOne
    {
        return $this->hasOne(MobileUserLastZikir::class);
    }

    public function readZikirs(): HasMany
    {
        return $this->hasMany(MobileUserReadZikir::class);
    }

    public function readDuas(): HasMany
    {
        return $this->hasMany(MobileUserReadDua::class);
    }

    public function zikirCounts(): HasMany
    {
        return $this->hasMany(MobileUserZikirCount::class);
    }
}
