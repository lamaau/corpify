<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip',
        'country',
        'region',
        'city',
        'latitude',
        'longitude',
        'device',
        'platform',
        'browser',
        'is_bot',
        'visit_count',
        'referrer',
        'url'
    ];
}
