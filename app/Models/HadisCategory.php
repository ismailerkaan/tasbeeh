<?php

namespace App\Models;

use Database\Factories\HadisCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HadisCategory extends Model
{
    /** @use HasFactory<HadisCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
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

    public function hadises(): HasMany
    {
        return $this->hasMany(Hadis::class);
    }
}
