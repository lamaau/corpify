<?php

namespace App\Models\Ability;

use App\Casts\DateObjectCast;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'summary',
    ];

    protected $casts = [
        'created_at' => DateObjectCast::class,
    ];

    public function scopePublic(Builder $query): void
    {
        $query->where($this->qualifyColumn('name'), '!=', 'Superadmin');
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class, 'role_has_abilities');
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_roles');
    }
}
