<?php

namespace App\Models;

use Database\Factories\HadisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hadis extends Model
{
    /** @use HasFactory<HadisFactory> */
    use HasFactory;

    protected $table = 'hadises';

    protected $fillable = [
        'hadis_category_id',
        'source',
        'hadis',
        'turkce_meali',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hadis_category_id' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(HadisCategory::class, 'hadis_category_id');
    }
}
