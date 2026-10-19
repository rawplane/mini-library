<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $sampleBooks = [
            // Teknologi & Komputer
            ['judul' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'penulis' => 'Robert C. Martin', 'penerbit' => 'Prentice Hall', 'tahun' => 2008, 'stok' => 5],
            ['judul' => 'Refactoring: Improving the Design of Existing Code', 'penulis' => 'Martin Fowler', 'penerbit' => 'Addison-Wesley', 'tahun' => 2018, 'stok' => 4],
            ['judul' => 'Designing Data-Intensive Applications', 'penulis' => 'Martin Kleppmann', 'penerbit' => "O'Reilly Media", 'tahun' => 2017, 'stok' => 6],
            ['judul' => 'Laravel Up & Running', 'penulis' => 'Matt Stauffer', 'penerbit' => "O'Reilly Media", 'tahun' => 2023, 'stok' => 7],
            ['judul' => 'The Pragmatic Programmer', 'penulis' => 'Andrew Hunt & David Thomas', 'penerbit' => 'Addison-Wesley', 'tahun' => 2019, 'stok' => 5],
            ['judul' => 'Introduction to Algorithms (CLRS)', 'penulis' => 'Thomas H. Cormen', 'penerbit' => 'MIT Press', 'tahun' => 2022, 'stok' => 3],
            ['judul' => 'Belajar Pemrograman Web Modern', 'penulis' => 'Rian Hidayat', 'penerbit' => 'Informatika', 'tahun' => 2023, 'stok' => 8],

            // Sains & Matematika
            ['judul' => 'A Brief History of Time', 'penulis' => 'Stephen Hawking', 'penerbit' => 'Bantam Books', 'tahun' => 1988, 'stok' => 4],
            ['judul' => 'Cosmos', 'penulis' => 'Carl Sagan', 'penerbit' => 'Random House', 'tahun' => 1980, 'stok' => 5],
            ['judul' => 'Kalkulus dan Geometri Analitis Jilid 1', 'penulis' => 'Edwin J. Purcell', 'penerbit' => 'Erlangga', 'tahun' => 2015, 'stok' => 6],
            ['judul' => 'Fisika Dasar untuk Universitas', 'penulis' => 'Halliday & Resnick', 'penerbit' => 'Erlangga', 'tahun' => 2016, 'stok' => 5],
            ['judul' => 'The Elegant Universe', 'penulis' => 'Brian Greene', 'penerbit' => 'W. W. Norton', 'tahun' => 2003, 'stok' => 3],
            ['judul' => 'Statistika Terapan untuk Penelitian', 'penulis' => 'Prof. Dr. Sugiyono', 'penerbit' => 'Alfabeta', 'tahun' => 2021, 'stok' => 7],

            // Fiksi & Sastra
            ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra / Lentera Dipantara', 'tahun' => 1980, 'stok' => 6],
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun' => 2005, 'stok' => 8],
            ['judul' => 'Hujan', 'penulis' => 'Tere Liye', 'penerbit' => 'Gramedia Pustaka Utama', 'tahun' => 2016, 'stok' => 5],
            ['judul' => 'Cantik Itu Luka', 'penulis' => 'Eka Kurniawan', 'penerbit' => 'Gramedia Pustaka Utama', 'tahun' => 2002, 'stok' => 4],
            ['judul' => 'Negeri 5 Menara', 'penulis' => 'A. Fuadi', 'penerbit' => 'Gramedia Pustaka Utama', 'tahun' => 2009, 'stok' => 6],
            ['judul' => '1984', 'penulis' => 'George Orwell', 'penerbit' => 'Secker & Warburg', 'tahun' => 1949, 'stok' => 5],
            ['judul' => 'To Kill a Mockingbird', 'penulis' => 'Harper Lee', 'penerbit' => 'J. B. Lippincott & Co.', 'tahun' => 1960, 'stok' => 4],

            // Sejarah & Budaya
            ['judul' => 'Sapiens: A Brief History of Humankind', 'penulis' => 'Yuval Noah Harari', 'penerbit' => 'Harper', 'tahun' => 2015, 'stok' => 5],
            ['judul' => 'Guns, Germs, and Steel', 'penulis' => 'Jared Diamond', 'penerbit' => 'W. W. Norton', 'tahun' => 1997, 'stok' => 4],
            ['judul' => 'Nusantara: Sejarah Indonesia', 'penulis' => 'Bernard H.M. Vlekke', 'penerbit' => 'KPG', 'tahun' => 2018, 'stok' => 3],
            ['judul' => 'Sejarah Nasional Indonesia', 'penulis' => 'Sartono Kartodirdjo', 'penerbit' => 'Balai Pustaka', 'tahun' => 2010, 'stok' => 4],
            ['judul' => 'Indonesia dalam Arus Sejarah', 'penulis' => 'Taufik Abdullah', 'penerbit' => 'Kemdikbud', 'tahun' => 2012, 'stok' => 2],
            ['judul' => 'Silk Roads: A New History of the World', 'penulis' => 'Peter Frankopan', 'penerbit' => 'Bloomsbury', 'tahun' => 2015, 'stok' => 4],

            // Pengembangan Diri
            ['judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'penerbit' => 'Avery', 'tahun' => 2018, 'stok' => 9],
            ['judul' => 'The 7 Habits of Highly Effective People', 'penulis' => 'Stephen R. Covey', 'penerbit' => 'Free Press', 'tahun' => 1989, 'stok' => 5],
            ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'penerbit' => 'Kompas', 'tahun' => 2018, 'stok' => 8],
            ['judul' => 'Deep Work', 'penulis' => 'Cal Newport', 'penerbit' => 'Grand Central Publishing', 'tahun' => 2016, 'stok' => 6],
            ['judul' => 'Mindset: The New Psychology of Success', 'penulis' => 'Carol S. Dweck', 'penerbit' => 'Ballantine Books', 'tahun' => 2006, 'stok' => 4],
            ['judul' => 'Make Time: How to Focus on What Matters', 'penulis' => 'Jake Knapp & John Zeratsky', 'penerbit' => 'Currency', 'tahun' => 2018, 'stok' => 5],

            // Bisnis & Ekonomi
            ['judul' => 'The Psychology of Money', 'penulis' => 'Morgan Housel', 'penerbit' => 'Harriman House', 'tahun' => 2020, 'stok' => 7],
            ['judul' => 'Rich Dad Poor Dad', 'penulis' => 'Robert T. Kiyosaki', 'penerbit' => 'Warner Books', 'tahun' => 1997, 'stok' => 6],
            ['judul' => 'Zero to One', 'penulis' => 'Peter Thiel', 'penerbit' => 'Crown Business', 'tahun' => 2014, 'stok' => 5],
            ['judul' => 'Good to Great', 'penulis' => 'Jim Collins', 'penerbit' => 'HarperBusiness', 'tahun' => 2001, 'stok' => 4],
            ['judul' => 'The Lean Startup', 'penulis' => 'Eric Ries', 'penerbit' => 'Crown Business', 'tahun' => 2011, 'stok' => 5],
            ['judul' => 'Ekonomi Manajerial', 'penulis' => 'Dominick Salvatore', 'penerbit' => 'Salemba Empat', 'tahun' => 2019, 'stok' => 3],

            // Agama & Filsafat
            ['judul' => 'Madilog', 'penulis' => 'Tan Malaka', 'penerbit' => 'Narasi', 'tahun' => 2014, 'stok' => 4],
            ['judul' => 'Tafsir Al-Azhar', 'penulis' => 'Prof. Dr. Hamka', 'penerbit' => 'Gema Insani', 'tahun' => 2015, 'stok' => 5],
            ['judul' => 'Dunia Sophie', 'penulis' => 'Jostein Gaarder', 'penerbit' => 'Mizan', 'tahun' => 1996, 'stok' => 6],
            ['judul' => 'Meditations', 'penulis' => 'Marcus Aurelius', 'penerbit' => 'Penguin Classics', 'tahun' => 2006, 'stok' => 5],
            ['judul' => 'Ihva Ulumuddin', 'penulis' => 'Imam Al-Ghazali', 'penerbit' => 'Republika Penerbit', 'tahun' => 2017, 'stok' => 4],
            ['judul' => 'Man’s Search for Meaning', 'penulis' => 'Viktor E. Frankl', 'penerbit' => 'Beacon Press', 'tahun' => 1959, 'stok' => 5],

            // Seni & Desain
            ['judul' => 'The Design of Everyday Things', 'penulis' => 'Don Norman', 'penerbit' => 'Basic Books', 'tahun' => 2013, 'stok' => 5],
            ['judul' => 'Don’t Make Me Think, Revisited', 'penulis' => 'Steve Krug', 'penerbit' => 'New Riders', 'tahun' => 2014, 'stok' => 6],
            ['judul' => 'Steal Like an Artist', 'penulis' => 'Austin Kleon', 'penerbit' => 'Workman Publishing', 'tahun' => 2012, 'stok' => 7],
            ['judul' => 'Grid Systems in Graphic Design', 'penulis' => 'Josef Müller-Brockmann', 'penerbit' => 'Niggli', 'tahun' => 1996, 'stok' => 3],
            ['judul' => 'Thinking with Type', 'penulis' => 'Ellen Lupton', 'penerbit' => 'Princeton Architectural Press', 'tahun' => 2010, 'stok' => 4],
            ['judul' => 'Universal Principles of Design', 'penulis' => 'William Lidwell', 'penerbit' => 'Rockport Publishers', 'tahun' => 2010, 'stok' => 4],
        ];

        // Insert curated 50 books with proper category mapping
        $catMap = [
            0 => 'Teknologi & Komputer',
            1 => 'Sains & Matematika',
            2 => 'Fiksi & Sastra',
            3 => 'Sejarah & Budaya',
            4 => 'Pengembangan Diri',
            5 => 'Bisnis & Ekonomi',
            6 => 'Agama & Filsafat',
            7 => 'Seni & Desain',
        ];

        $chunkSize = 6;
        $catIndex = 0;
        foreach ($sampleBooks as $idx => $bookData) {
            $categoryName = $catMap[$catIndex] ?? $catMap[0];
            $category = $categories->firstWhere('nama', $categoryName) ?? $categories->first();

            Book::firstOrCreate(
                ['judul' => $bookData['judul']],
                [
                    'category_id' => $category->id,
                    'penulis' => $bookData['penulis'],
                    'penerbit' => $bookData['penerbit'],
                    'tahun' => $bookData['tahun'],
                    'stok' => $bookData['stok'],
                    'cover' => null,
                ]
            );

            if (($idx + 1) % 6 === 0 && $catIndex < 7) {
                $catIndex++;
            }
        }
    }
}
