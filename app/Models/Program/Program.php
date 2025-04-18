<?php

namespace App\Models\Program;

use App\Casts\DateObjectCast;
use App\Models\Traits\HasAuthor;
use App\Models\Traits\HasQueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasAuthor,
        HasQueryFilter;

    protected $fillable = [
        'name',
        'summary',
    ];

    protected $casts = [
        'created_at' => DateObjectCast::class,
        'updated_at' => DateObjectCast::class,
    ];

    public function features(): HasMany
    {
        return $this->hasMany(ProgramFeature::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(WorkProgram::class);
    }
}
