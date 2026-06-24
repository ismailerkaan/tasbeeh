<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialDaySharingImage extends Model
{
    protected $fillable = ['path', 'original_name', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SpecialDaySharingCampaign::class, 'special_day_sharing_campaign_id');
    }
}
