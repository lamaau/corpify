<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'name',
        'value',
        'settingable_type',
        'settingable_id',
        'context',
        'autoload',
        'public',
        'created_by',
    ];
}
