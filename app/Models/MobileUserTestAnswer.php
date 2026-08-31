<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileUserTestAnswer extends Model
{
    protected $fillable = [
        'mobile_user_test_run_id',
        'test_question_id',
        'question_order',
        'selected_option_id',
        'correct_option_id',
        'is_correct',
        'score_earned',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'mobile_user_test_run_id' => 'integer',
            'test_question_id' => 'integer',
            'question_order' => 'integer',
            'is_correct' => 'boolean',
            'score_earned' => 'integer',
            'answered_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(MobileUserTestRun::class, 'mobile_user_test_run_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id');
    }
}