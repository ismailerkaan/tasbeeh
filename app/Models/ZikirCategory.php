<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZikirCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ZikirCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
        'description',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function zikirs(): HasMany
    {
        return $this->hasMany(Zikir::class);
    }
}
