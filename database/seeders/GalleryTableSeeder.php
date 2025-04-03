<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery\Gallery;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class GalleryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Make sure temp directory exists
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        for ($i = 0; $i < 22; $i++) {
            try {
                // Download a random image first
                $imageUrl = "https://picsum.photos/800/600";
                $response = Http::get($imageUrl);
                
                // Only proceed if the image was successfully downloaded
                if ($response->successful()) {
                    $imageContents = $response->body();
                    $tempImagePath = storage_path('app/public/temp/' . uniqid() . '.jpg');
                    
                    // Save the image to a temporary file
                    if (file_put_contents($tempImagePath, $imageContents)) {
                        // Check if the file exists and is valid
                        if (file_exists($tempImagePath) && filesize($tempImagePath) > 0) {
                            // Create gallery entry
                            $gallery = Gallery::create([
                                'sort' => $i + 1,
                                'caption' => $faker->sentence,
                                'featured' => $faker->boolean,
                                'created_by' => User::first()->id,
                            ]);
                            
                            // Attach image using Spatie Media Library
                            $gallery->addMedia($tempImagePath)
                                ->toMediaCollection('gallery');
                                
                            $this->command->info("Created gallery item #" . ($i + 1) . " with image");
                        } else {
                            $this->command->error("Failed to create valid image file for item #" . ($i + 1));
                        }
                        
                        // Clean up temp file
                        @unlink($tempImagePath);
                    } else {
                        $this->command->error("Failed to save image file for item #" . ($i + 1));
                    }
                } else {
                    $this->command->error("Failed to download image for item #" . ($i + 1) . ": " . $response->status());
                }
            } catch (\Exception $e) {
                $this->command->error("Error creating gallery item #" . ($i + 1) . ": " . $e->getMessage());
                Log::error("Gallery seeder error: " . $e->getMessage());
            }
        }
        
        $this->command->info("Gallery seeding completed");
    }
}