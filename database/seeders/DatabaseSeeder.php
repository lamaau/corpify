<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserTableSeeder::class)
            ->call(ProgramTableSeeder::class)
            ->call(FaqTableSeeder::class)
            ->call(PositionTableSeeder::class)
            ->call(PositionCategoryTableSeeder::class)
            ->call(PostCategoryTableSeeder::class)
            ->call(PostTableSeeder::class)
            ->call(WorkProgramTableSeeder::class);
        // ->call(GalleryTableSeeder::class);
    }
}
