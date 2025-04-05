<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\User\UserProfile;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminRole = Role::create(['name' => 'superadmin']);
        $memberRole = Role::create(['name' => 'member']);

        User::factory(100)->create()->each(function ($user, $index) use ($memberRole) {
            UserProfile::factory()->create(['user_id' => $user->id, 'name' => "User {$index} profile"]);
            $user->assignRole($memberRole);
        });

        $superadmin = User::factory()->create(['email' => 'superadmin@mail.com'])->assignRole($superadminRole);
        UserProfile::factory()->create(['user_id' => $superadmin->id, 'name' => 'Superadmin']);
    }
}
