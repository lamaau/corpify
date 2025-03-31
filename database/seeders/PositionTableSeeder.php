<?php

namespace Database\Seeders;

use App\Models\User\Position;
use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    public function run()
    {
        collect([
            'Ketua Umum',
            'Wakil Ketua Umum',
            'Sekretaris',
            'Bendahara',
            'Anggota'
        ])->map(fn($name) => Position::create([
            'position_name' => $name,
        ]));
    }
}
