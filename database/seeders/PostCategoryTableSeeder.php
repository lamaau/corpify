<?php

namespace Database\Seeders;

use App\Models\Post\PostCategory;
use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        collect([
            'Kegiatan',
            'Berita',
            'Artikel',
        ])->map(function ($name) use ($user) {
            return PostCategory::create([
                'category_name' => $name,
                'category_summary' => "Summary of $name",
                'created_by' => $user->id,
            ]);
        });
    }
}
