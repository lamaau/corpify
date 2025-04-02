<?php

namespace App\Models\Regulation;

use App\Casts\DateObjectCast;
use App\Models\Traits\HasAuthor;
use App\Models\Traits\HasQueryFilter;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Regulation extends Model implements HasMedia
{
    use HasAuthor,
        HasQueryFilter,
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

    protected $casts = [
        'created_at' => DateObjectCast::class,
        'updated_at' => DateObjectCast::class,
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
