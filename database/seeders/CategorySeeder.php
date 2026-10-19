<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Teknologi & Komputer',
            'Sains & Matematika',
            'Fiksi & Sastra',
            'Sejarah & Budaya',
            'Pengembangan Diri',
            'Bisnis & Ekonomi',
            'Agama & Filsafat',
            'Seni & Desain',
        ];

        foreach ($categories as $nama) {
            Category::firstOrCreate(['nama' => $nama]);
        }
    }
}
