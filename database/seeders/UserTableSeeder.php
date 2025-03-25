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
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'cashier']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@apotekta.ahadunstudio.id',
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'Cashirt',
            'email' => 'cashier@apotekta.ahadunstudio.id',
        ])->assignRole('cashier');
    }
}
