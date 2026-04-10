<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zikir extends Model
{
    /** @use HasFactory<\Database\Factories\ZikirFactory> */
    use HasFactory;

    protected $fillable = [
        'zikir_category_id',
        'zikir',
        'anlami',
        'fazileti',
        'hedef',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'zikir_category_id' => 'integer',
            'hedef' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ZikirCategory::class, 'zikir_category_id');
    }

    public function dailyZikrs(): HasMany
    {
        return $this->hasMany(DailyZikr::class);
    }
}
