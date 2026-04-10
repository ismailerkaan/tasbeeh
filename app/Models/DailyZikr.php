<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyZikr extends Model
{
    /** @use HasFactory<\Database\Factories\DailyZikrFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'locale',
        'title',
        'zikir_id',
        'transliteration',
        'meaning',
        'virtue_short',
        'count_suggestion',
        'share_text',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'zikir_id' => 'integer',
            'count_suggestion' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function zikir(): BelongsTo
    {
        return $this->belongsTo(Zikir::class);
    }
}
