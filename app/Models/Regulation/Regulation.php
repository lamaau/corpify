<?php

namespace App\Models\Regulation;

use App\Models\Traits\HasAuthor;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Regulation extends Model implements HasMedia
{
    use HasAuthor,
        InteractsWithMedia;

    protected $fillable = [
        'title',
        'summary',
    ];

    protected $hidden = [
        'media',
    ];

    protected $appends = [
        'file',
    ];

    public function file(): Attribute
    {
        return Attribute::make(get: fn() => $this->media->first()->getUrl());
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('regulations')
            ->acceptsMimeTypes(['application/pdf'])
            ->singleFile();
    }
}
