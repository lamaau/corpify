<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthor
{
    public static function bootHasAuthor()
    {
        static::retrieved(function ($model) {
            $model->makeHidden('created_by');
            $model->loadMissing('author');
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
