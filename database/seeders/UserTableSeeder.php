<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'superadmin@mail.com',
        ])->assignRole('superadmin');
    }
}
