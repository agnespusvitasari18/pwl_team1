<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $majors = [
            'Akuntansi dan Keuangan Lembaga',
            'Desain Komunikasi Visual',
            'Perhotelan',
            'Pengembangan Perangkat Lunak',
            'Otomatisasi dan Tata Kelola Perkantoran',
            'Bisnis Ritel',
            'Usaha Perjalanan Wisata',
            'Tata Boga',
        ];

        foreach ($majors as $name) {
            Major::firstOrCreate(['name' => $name]);
        }
    }
}