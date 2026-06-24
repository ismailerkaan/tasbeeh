<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecialDaySharingCampaign extends Model
{
    protected $fillable = ['title', 'message', 'publish_date', 'is_active'];

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(SpecialDaySharingImage::class)->orderBy('sort_order')->orderBy('id');
    }
}
