<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TestCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function levels(): HasMany
    {
        return $this->hasMany(TestLevel::class);
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(TestQuestion::class, TestLevel::class, 'test_category_id', 'test_level_id');
    }
}