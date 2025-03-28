<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = ['name', 'summary', 'created_by'];

    public function features(): HasMany
    {
        return $this->hasMany(ProgramFeature::class);
    }
}
