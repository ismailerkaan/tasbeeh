<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Dua extends Model
{
    /** @use HasFactory<\Database\Factories\DuaFactory> */
    use HasFactory;

    protected $fillable = [
        'dua_category_id',
        'source',
        'dua',
        'turkce_meali',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dua_category_id' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DuaCategory::class, 'dua_category_id');
    }
}
