<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthor
{
    public static function bootHasAuthor()
    {
        static::creating(function ($model) {
            $model->mergeFillable(['created_by'])->fill(['created_by' => user()->id]);
        });

        static::retrieved(function ($model) {
            $model->makeHidden('created_by')->loadMissing('author');
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
