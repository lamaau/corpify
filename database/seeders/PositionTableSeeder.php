<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\Position;
use Illuminate\Database\Seeder;
use App\Models\User\UserProfile;
use Illuminate\Support\Facades\DB;
use App\Models\User\PositionCategory;
use App\Models\User\PositionAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionTableSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // 🔹 Create Users with Profiles
            $users = User::factory()->count(20)->create()->each(function ($user) {
                UserProfile::factory()->create(['user_id' => $user->id]);
            });

            // 🔹 Create Position Categories (DPP, DPC, etc.)
            $categories = collect([
                'Dewan Pengurus Pusat (DPP)',
                'Dewan Pengurus Cabang (DPC)',
                'Dewan Penasehat'
            ])->map(fn($name) => PositionCategory::create([
                'position_category_name' => $name,
                'created_by' => $users->random()->id,
            ]));

            // 🔹 Create Positions (Ketua, Wakil, etc.)
            $positions = collect([
                'Ketua',
                'Wakil Ketua',
                'Sekretaris',
                'Bendahara',
                'Anggota'
            ])->map(fn($name) => Position::create([
                'position_name' => $name,
                'sort' => rand(1, 5),
                'created_by' => $users->random()->id,
            ]));

            // 🔹 Function to Create Recursive Assignments
            $createRecursiveAssignments = function ($parent, $depth = 1) use ($users, $positions, $categories, &$createRecursiveAssignments) {
                if ($depth > 3) return; // Limit recursion depth to 3

                $numChildren = rand(1, 3); // Each parent can have 1-3 children
                for ($i = 0; $i < $numChildren; $i++) {
                    $childUser = $users->random();

                    $childAssignment = PositionAssignment::create([
                        'user_id' => $childUser->id,
                        'position_id' => $positions->random()->id,
                        'position_category_id' => $categories->random()->id,
                        'parent_id' => $parent->id, // Recursive link
                    ]);

                    // 🔁 Recursively create children for this child
                    if (rand(0, 1)) { // Randomly decide whether to add more depth
                        $createRecursiveAssignments($childAssignment, $depth + 1);
                    }
                }
            };

            // 🔹 Assign Users to Positions in Different Categories (Top Level)
            $assignments = collect();
            foreach ($users->take(5) as $user) { // Limit to 5 top-level positions
                $topAssignment = PositionAssignment::create([
                    'user_id' => $user->id,
                    'position_id' => $positions->random()->id,
                    'position_category_id' => $categories->random()->id,
                    'parent_id' => null, // Top-level position
                ]);

                $assignments->push($topAssignment);

                // 🔥 Create recursive children under this top-level position
                $createRecursiveAssignments($topAssignment);
            }
        });

        $this->command->info('✅ PositionSeeder completed with deep hierarchical data.');
    }
}
