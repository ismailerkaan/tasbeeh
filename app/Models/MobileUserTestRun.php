<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileUserTestRun extends Model
{
    protected $fillable = [
        'mobile_user_id',
        'test_level_id',
        'score',
        'correct_count',
        'total_questions',
        'best_streak',
        'continued_with_ad',
        'ended_reason',
        'completed',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'mobile_user_id' => 'integer',
            'test_level_id' => 'integer',
            'score' => 'integer',
            'correct_count' => 'integer',
            'total_questions' => 'integer',
            'best_streak' => 'integer',
            'continued_with_ad' => 'boolean',
            'completed' => 'boolean',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function mobileUser(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(TestLevel::class, 'test_level_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(MobileUserTestAnswer::class);
    }
}