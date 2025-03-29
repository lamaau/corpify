<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MemberTableSeeder extends Seeder
{
    public function run()
    {
        $adminUser = User::first();

        // Seed member positions
        $positions = [
            ['member_position_name' => 'Ketua', 'sort' => 1],
            ['member_position_name' => 'Wakil', 'sort' => 2],
            ['member_position_name' => 'Sekretaris', 'sort' => 3],
            ['member_position_name' => 'Bendahara', 'sort' => 4],
            ['member_position_name' => 'Anggota', 'sort' => 5],
        ];

        foreach ($positions as &$position) {
            $position['created_by'] = $adminUser->id;
            $position['created_at'] = now();
            $position['updated_at'] = now();
        }

        DB::table('member_positions')->insert($positions);

        // Seed member categories
        $categories = [
            ['member_category_name' => 'Pengurus Pusat'],
            ['member_category_name' => 'Dewan Penasehat'],
            ['member_category_name' => 'Dewan Pembina'],
            ['member_category_name' => 'Dewan Pakar'],
        ];

        foreach ($categories as &$category) {
            $category['created_by'] = $adminUser->id;
            $category['created_at'] = now();
            $category['updated_at'] = now();
        }

        DB::table('member_categories')->insert($categories);
    }
}
