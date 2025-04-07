<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\Faq\Faq;
use Illuminate\Database\Seeder;

class FaqTableSeeder extends Seeder
{
    public function run()
    {
        // Get an existing user or create one (to avoid foreign key errors)
        $user = User::first() ?? User::factory()->create();

        // Insert sample FAQs
        Faq::insert([
            [
                'question' => 'What is Laravel?',
                'answer' => 'Laravel is a PHP framework for web application development.',
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'How do I install Laravel?',
                'answer' => 'You can install Laravel via Composer using `composer create-project laravel/laravel example-app`.',
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What is Eloquent?',
                'answer' => 'Eloquent is Laravel’s ORM for database operations.',
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
