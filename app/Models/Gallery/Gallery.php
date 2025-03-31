<?php

namespace App\Models\Gallery;

use App\Models\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use HasAuthor,
        InteractsWithMedia;

    protected $fillable = [
        'caption',
        'created_by',
    ];

    protected $hidden = [
        'media',
    ];

    protected $appends = [
        'file',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile();
    }

    public function file(): Attribute
    {
        $media = $this->media('gallery')->first();

        return Attribute::make(get: fn() => [
            'file_url' => $media->getUrl(),
            'file_name' => $media->file_name,
            'file_size' => formatFileSize($media->size),
            'file_mime_type' => $media->mime_type,
        ]);
    }
}
