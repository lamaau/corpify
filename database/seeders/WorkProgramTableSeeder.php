<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Enums\Program\WorkProgramStatus;
use Illuminate\Database\Seeder;
use App\Models\Program\Program;
use App\Models\Program\WorkProgram;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkProgramTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $user = User::first();
        $categories = Program::all();

        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Skipping post seeding.');
            return;
        }

        for ($i = 1; $i <= 100; $i++) {
            $category = $categories->random();
            $title = $faker->sentence(6);
            $slug = Str::slug($title . '-' . Str::random(5));

            // Generate a long, well-formatted WYSIWYG-style post body
            $body = "<h2>{$faker->sentence(5)}</h2>";
            $body .= "<p>{$faker->paragraph(8)}</p>";
            $body .= "<p><strong>{$faker->sentence(10)}</strong></p>";
            $body .= "<p>{$faker->paragraph(10)}</p>";
            $body .= "<h2>{$faker->sentence(4)}</h2>";
            $body .= "<p>{$faker->paragraph(6)}</p>";

            $body .= "<ul>" .
                "<li>{$faker->sentence(12)}</li>" .
                "<li>{$faker->sentence(12)}</li>" .
                "<li>{$faker->sentence(12)}</li>" .
                "</ul>";

            $body .= "<blockquote>{$faker->sentence(15)}</blockquote>";
            $body .= "<p>{$faker->paragraph(7)}</p>";
            $body .= "<h2>{$faker->sentence(5)}</h2>";
            $body .= "<p>{$faker->paragraph(10)}</p>";
            $body .= "<p>For more details, visit <a href='#'>{$faker->domainName()}</a>.</p>";

            WorkProgram::create([
                'title' => $title,
                'slug' => $slug,
                'summary' => $faker->sentence(20),
                'body' => $body,
                'status' => collect([WorkProgramStatus::Published(), WorkProgramStatus::Draft()])->random(),
                'program_id' => $category->id,
                'created_by' => $user->id,
            ]);
        }
    }
}
