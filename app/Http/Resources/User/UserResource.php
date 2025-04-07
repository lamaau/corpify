<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * I just want to handle case for superadmin, cause __ONLY SUPERADMIN__ does not have profile
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'profile' => match ($this->profile) {
                null => [
                    'name' => 'Superadmin',
                    'avatar' => 'https://ui-avatars.com/api/?name=Superadmin&amp;color=FFFFFF&amp;background=09090b',
                ],
                default => $this->profile,
            },
            'roles' => $this->whenLoaded('roles')
        ];
    }
}
