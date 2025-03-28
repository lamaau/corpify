<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramFeature extends Model
{
    protected $fillable = [
        'icon',
        'program_id',
        'feature_name',
        'is_available'
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
