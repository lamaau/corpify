<?php

namespace App\Models\Gallery;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'caption',
        'created_by',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')->singleFile();
    }
}
