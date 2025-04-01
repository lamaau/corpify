<?php

namespace App\Models\Post;

use App\Casts\DateObjectCast;
use App\Enums\Post\PostStatus;
use App\Models\Traits\HasAuthor;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\HasQueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\WithThumbnailMediaCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model implements HasMedia
{
    use HasAuthor,
        HasQueryFilter,
        WithThumbnailMediaCollection;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'status',
        'summary',
        'content',
        'created_by',
        'post_category_id',
    ];

    protected $hidden = [
        'media',
        'post_category_id',
    ];

    protected $appends = [
        'thumbnail',
    ];

    protected $with = [
        'category',
    ];

    protected $casts = [
        'created_at' => DateObjectCast::class,
        'updated_at' => DateObjectCast::class,
    ];

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => PostStatus::from($value)->attributes(),
        );
    }

    public function thumbnail(): Attribute
    {
        return Attribute::make(get: function () {
            if ($media = $this->media->first()) {
                return $media->getUrl();
            }

            return url('images/placeholder.png');
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }
}
