<?php

namespace App\Models\News;

use App\Models\Traits\HasAuthor;
use App\Models\Traits\HasContent;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class News extends Model implements HasMedia
{
    use HasAuthor,
        HasContent;

    protected $fillable = [
        'title',
        'slug',
        'created_by',
    ];

    protected $hidden = [
        'media',
        'created_by',
        'contentRelation',
    ];

    protected $with = [
        'author',
    ];

    protected $appends = [
        'content',
        'thumbnail',
    ];

    public function content(): Attribute
    {
        return Attribute::make(get: fn() => $this->contentRelation?->content ?? null);
    }

    public function thumbnail(): Attribute
    {
        return Attribute::make(get: fn() => $this->media->first()->getUrl());
    }
}
