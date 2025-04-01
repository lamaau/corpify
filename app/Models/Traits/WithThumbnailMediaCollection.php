<?php

namespace App\Models\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;

trait WithThumbnailMediaCollection
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnails')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile();
    }
}
