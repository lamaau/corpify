<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\Ability\Role;
use App\Models\Ability\Ability;
use Illuminate\Database\Seeder;
use App\Models\User\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminUser = User::factory()->create(['email' => 'superadmin@mail.com']);

        $admin = User::factory()->create(['email' => 'admin@mail.com']);
        UserProfile::factory()->create(['user_id' => $admin->id, 'name' => "User Admin profile"]);

        User::factory(5)->create()->each(function ($user, $index) {
            UserProfile::factory()->create(['user_id' => $user->id, 'name' => "User {$index} profile"]);
        });

        collect(config('fixtures'))->each(function ($fixture) {
            Ability::firstOrCreate(['name' => "manage {$fixture}"]);
        });

        $superadminRole = Role::create(['name' => 'Superadmin']);
        collect(['Admin', 'Editor', 'Member'])
            ->each(fn($name) => Role::create(['name' => $name]));

        $superadminRole->abilities()->sync(Ability::pluck('id'));
        $superadminUser->roles()->sync([$superadminRole->id]);
    }
}
