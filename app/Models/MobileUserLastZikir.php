<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileUserLastZikir extends Model
{
    /** @use HasFactory<\Database\Factories\MobileUserLastZikirFactory> */
    use HasFactory;

    protected $fillable = [
        'mobile_user_id',
        'content_id',
        'name',
        'count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mobile_user_id' => 'integer',
            'count' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class, 'mobile_user_id');
    }
}
