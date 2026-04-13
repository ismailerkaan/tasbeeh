<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileFeedback extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_REVIEWED = 'reviewed';

    protected $fillable = [
        'user_identifier',
        'full_name',
        'message',
        'fcm_token',
        'platform',
        'device_model',
        'os_version',
        'city',
        'district',
        'status',
    ];
}
