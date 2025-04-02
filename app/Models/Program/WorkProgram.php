<?php

namespace App\Models\Program;

use App\Casts\DateObjectCast;
use App\Models\Traits\HasAuthor;
use App\Models\Traits\HasQueryFilter;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Program\WorkProgramStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\WithThumbnailMediaCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class WorkProgram extends Model implements HasMedia
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
        'program_id',
    ];

    protected $hidden = [
        'media',
        'program_id',
    ];

    protected $appends = [
        'thumbnail',
    ];

    protected $with = [
        'program',
    ];

    protected $casts = [
        'created_at' => DateObjectCast::class,
        'updated_at' => DateObjectCast::class,
    ];

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => WorkProgramStatus::from($value)->attributes(),
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

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
