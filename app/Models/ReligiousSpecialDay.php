<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReligiousSpecialDay extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'kandil_geceleri' => ['label' => 'Kandil Geceleri', 'color' => '#8B5CF6'],
        'dini_bayramlar' => ['label' => 'Dini Bayramlar', 'color' => '#10B981'],
        'mubarek_gunler_aylar' => ['label' => 'Mübarek Günler & Aylar', 'color' => '#D97706'],
    ];

    protected $fillable = [
        'title',
        'category',
        'event_date',
        'hijri_date',
        'short_description',
        'description',
        'recommendations',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'recommendations' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
