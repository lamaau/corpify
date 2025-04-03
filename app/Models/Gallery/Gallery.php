<?php

namespace App\Models\Gallery;

use App\Models\Traits\HasAuthor;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\HasQueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Gallery extends Model implements HasMedia
{
    use HasAuthor,
        HasQueryFilter,
        InteractsWithMedia;

    protected $fillable = [
        'sort',
        'caption',
        'featured',
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
            'file_url' => $media?->getUrl(),
            'file_name' => $media?->file_name,
            'file_size' => $media ? formatFileSize($media?->size) : 0,
            'file_mime_type' => $media?->mime_type,
        ]);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    public function scopeNotFeatured(Builder $query): void
    {
        $query->where('featured', false);
    }
}
