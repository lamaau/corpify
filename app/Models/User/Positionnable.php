<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Positionnable extends Model
{
    use HasFactory;

    protected $fillable = [
        'positionnable_id',
        'positionnable_type',
        'position_assignment_id',
    ];

    public function positionAssignment(): BelongsTo
    {
        return $this->belongsTo(PositionAssignment::class);
    }

    public function positionnable(): MorphTo
    {
        return $this->morphTo();
    }
}
