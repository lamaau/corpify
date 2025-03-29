<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Content extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'content',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
