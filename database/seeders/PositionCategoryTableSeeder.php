<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use App\Models\User\PositionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        collect([
            'Dewan Pengurus Pusat',
            'Dewan Penasehat',
            'Dewan Pembina',
        ])->map(fn($name) => PositionCategory::create([
            'position_category_name' => $name,
            'position_category_summary' => "Summary of {$name}",
            'created_by' => $user->id,
        ]));
    }
}
