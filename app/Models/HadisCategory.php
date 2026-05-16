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
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function hadises(): HasMany
    {
        return $this->hasMany(Hadis::class);
    }
}
