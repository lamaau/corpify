<?php

namespace App\Models\Traits;

use App\Models\User\Positionnable;

trait HasPositions
{
    public function positions()
    {
        return $this->morphMany(Positionnable::class, 'positionnable');
    }
}
