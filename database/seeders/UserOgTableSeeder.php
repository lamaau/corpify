<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\User\Position;
use Illuminate\Database\Seeder;
use App\Models\User\UserProfile;
use App\Models\User\PositionCategory;
use App\Models\User\PositionAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserOgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Users
        $admin = User::first();
        $user1 = User::factory()->create(['email' => 'user1@mail.com']);
        $user2 = User::factory()->create(['email' => 'user2@mail.com']);

        // Create Profiles
        UserProfile::factory()->create(['user_id' => $admin->id, 'name' => 'Admin Profile']);
        UserProfile::factory()->create(['user_id' => $user1->id, 'name' => 'User 1 Profile']);
        UserProfile::factory()->create(['user_id' => $user2->id, 'name' => 'User 2 Profile']);

        // Create Positions
        $ketua = Position::create(['position_name' => 'Ketua', 'sort' => 1, 'created_by' => $admin->id]);
        $wakilKetua = Position::create(['position_name' => 'Wakil Ketua', 'sort' => 2, 'created_by' => $admin->id]);

        // Create Position Categories
        $dpp = PositionCategory::create(['position_category_name' => 'DPP', 'created_by' => $admin->id]);
        $dpc = PositionCategory::create(['position_category_name' => 'DPC', 'created_by' => $admin->id]);

        // Assign Users to Positions in Different Categories
        $ketuaDPP = PositionAssignment::create([
            'user_id' => $user1->id,
            'position_id' => $ketua->id,
            'position_category_id' => $dpp->id,
            'parent_id' => null
        ]);

        $wakilKetuaDPP = PositionAssignment::create([
            'user_id' => $user2->id,
            'position_id' => $wakilKetua->id,
            'position_category_id' => $dpp->id,
            'parent_id' => $ketuaDPP->id
        ]);

        // Assign same user to different category (DPC)
        $ketuaDPC = PositionAssignment::create([
            'user_id' => $user1->id,
            'position_id' => $ketua->id,
            'position_category_id' => $dpc->id,
            'parent_id' => null
        ]);

        $wakilKetuaDPC = PositionAssignment::create([
            'user_id' => $user2->id, // Same user as Wakil Ketua DPP
            'position_id' => $wakilKetua->id,
            'position_category_id' => $dpc->id,
            'parent_id' => $ketuaDPC->id
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
