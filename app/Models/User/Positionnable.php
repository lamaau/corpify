<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Positionnable extends Model
{
    use HasFactory;

    protected $fillable = ['position_assignment_id', 'positionnable_id', 'positionnable_type'];

    public function positionAssignment()
    {
        return $this->belongsTo(PositionAssignment::class);
    }

    public function positionnable(): MorphTo
    {
        return $this->morphTo();
    }
}
