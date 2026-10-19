<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggotas = User::where('role', 'anggota')->get();
        $books = Book::all();

        if ($anggotas->isEmpty() || $books->isEmpty()) {
            return;
        }

        // 1. Peminjaman selesai (dikembalikan tepat waktu)
        $loan1 = Loan::create([
            'user_id' => $anggotas[0]->id,
            'tanggal_pinjam' => Carbon::now()->subDays(20),
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(13),
            'tanggal_kembali' => Carbon::now()->subDays(15),
            'status' => 'dikembalikan',
            'denda' => 0,
        ]);
        LoanDetail::create(['loan_id' => $loan1->id, 'book_id' => $books[0]->id]);
        LoanDetail::create(['loan_id' => $loan1->id, 'book_id' => $books[1]->id]);

        // 2. Peminjaman selesai dengan keterlambatan (denda dihitung Rp1.000/hari)
        $loan2 = Loan::create([
            'user_id' => $anggotas[1]->id ?? $anggotas[0]->id,
            'tanggal_pinjam' => Carbon::now()->subDays(25),
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(18),
            'tanggal_kembali' => Carbon::now()->subDays(14), // telat 4 hari
            'status' => 'dikembalikan',
            'denda' => 4000,
        ]);
        LoanDetail::create(['loan_id' => $loan2->id, 'book_id' => $books[2]->id]);

        // 3. Peminjaman sedang aktif (belum jatuh tempo)
        $loan3 = Loan::create([
            'user_id' => $anggotas[0]->id,
            'tanggal_pinjam' => Carbon::now()->subDays(3),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(4),
            'tanggal_kembali' => null,
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        LoanDetail::create(['loan_id' => $loan3->id, 'book_id' => $books[3]->id]);
        // Kurangi stok untuk buku yang sedang aktif dipinjam
        $books[3]->decrement('stok', 1);

        // 4. Peminjaman aktif yang sudah melewati jatuh tempo (terlambat)
        $loan4 = Loan::create([
            'user_id' => $anggotas[2]->id ?? $anggotas[0]->id,
            'tanggal_pinjam' => Carbon::now()->subDays(12),
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(5),
            'tanggal_kembali' => null,
            'status' => 'terlambat',
            'denda' => 5000, // 5 hari telat
        ]);
        LoanDetail::create(['loan_id' => $loan4->id, 'book_id' => $books[4]->id]);
        $books[4]->decrement('stok', 1);

        // 5. Riwayat peminjaman bulan-bulan sebelumnya (untuk data grafik 6 bulan terakhir)
        for ($i = 1; $i <= 5; $i++) {
            $pastDate = Carbon::now()->subMonths($i)->startOfMonth()->addDays(rand(2, 20));
            $pastLoan = Loan::create([
                'user_id' => $anggotas->random()->id,
                'tanggal_pinjam' => $pastDate,
                'tanggal_jatuh_tempo' => (clone $pastDate)->addDays(7),
                'tanggal_kembali' => (clone $pastDate)->addDays(rand(3, 7)),
                'status' => 'dikembalikan',
                'denda' => 0,
            ]);
            LoanDetail::create([
                'loan_id' => $pastLoan->id,
                'book_id' => $books->random()->id,
            ]);
        }
    }
}
