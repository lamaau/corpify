<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

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
