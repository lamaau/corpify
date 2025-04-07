<?php

namespace App\Models\Traits;

use App\Models\Ability\Role;
use App\Models\Ability\Ability;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasAbility
{
    public function hasAbility(string $ability): bool
    {
        return $this->getAllAbilities()->contains($ability);
    }

    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles');
    }

    public function abilities(): MorphToMany
    {
        return $this->morphToMany(Ability::class, 'model', 'model_has_abilities');
    }

    public function getAllAbilities(): \Illuminate\Support\Collection
    {
        $roleAbilities = $this->roles()->with('abilities')->get()
            ->pluck('abilities')
            ->flatten()
            ->pluck('name');

        $directAbilities = $this->abilities->pluck('name');

        return $roleAbilities->merge($directAbilities)->unique();
    }

    public function syncRolesAndAbilities(?int $roleId, array $abilityIds = []): void
    {
        if ($roleId) {
            $this->roles()->sync([$roleId]);
        } else {
            $this->roles()->detach(); // optional if nullable
        }

        $this->abilities()->sync($abilityIds);
    }
}
