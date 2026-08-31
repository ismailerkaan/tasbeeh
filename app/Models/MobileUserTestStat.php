<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileUserTestStat extends Model
{
    protected $fillable = [
        'mobile_user_id',
        'total_score',
        'best_run_score',
        'completed_runs',
        'answered_questions',
        'level_best_scores',
    ];

    protected function casts(): array
    {
        return [
            'mobile_user_id' => 'integer',
            'total_score' => 'integer',
            'best_run_score' => 'integer',
            'completed_runs' => 'integer',
            'answered_questions' => 'integer',
            'level_best_scores' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function mobileUser(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class);
    }
}