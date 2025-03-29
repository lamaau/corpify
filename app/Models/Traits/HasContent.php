<?php

namespace App\Models\Traits;

use App\Models\Content\Content;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasContent
{
    use WithThumbnailMediaCollection;

    public static function bootHasContent()
    {
        static::retrieved(function ($model) {
            $model->makeHidden('contentRelation');
        });
    }

    public function contentRelation(): MorphOne
    {
        return $this->morphOne(Content::class, 'model');
    }
}
