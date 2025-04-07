<?php

namespace Database\Seeders;

use App\Models\Program\Program;
use Illuminate\Database\Seeder;
use App\Models\Program\ProgramFeature;
use App\Models\User\User;

class ProgramTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Bidang Keanggotaan dan Organisasi',
                'features' => ['Pendaftaran Anggota', 'Pemeliharaan Data Keanggotaan', 'SDM Aparatur Dalam Sepekan', 'Kartu Anggota Digital'],
            ],
            [
                'name' => 'Bidang Pengembangan Kapasitas Insani',
                'features' => ['Komunitas Belajar', 'Sayembara Literasi SDM Aparatur', 'SEGERA'],
            ],
            [
                'name' => 'Bidang Hukum dan Advokasi',
                'features' => ['Pengesahan Badan Hukum Perkumpulan', 'SEGERA', 'SEGERA'],
            ],
            [
                'name' => 'Bidang Hubungan Masyarakat dan Kerja Sama',
                'features' => ['Layanan Informasi Kehumasan', 'Penulisan dan Publikasi', 'Pengelolaan Media Elektronik dan Sosial'],
            ],
            [
                'name' => 'Bidang Sumber Pendanaan Organisasi',
                'features' => ['Merchandise', 'SEGERA', 'SEGERA'],
            ],
        ];

        foreach ($programs as $data) {
            $program = Program::create(['name' => $data['name'], 'created_by' => User::first()->id]);

            foreach ($data['features'] as $feature) {
                ProgramFeature::create([
                    'program_id' => $program->id,
                    'feature_name' => $feature,
                    'icon' => '📚',
                    'is_available' => $feature !== 'SEGERA',
                ]);
            }
        }
    }
}
