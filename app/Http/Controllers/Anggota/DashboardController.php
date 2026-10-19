<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        // Pinjaman aktif user
        $activeLoans = $user->loans()
            ->with('books.category')
            ->active()
            ->latest('tanggal_pinjam')
            ->get();

        $activeBorrowedCount = $user->activeBorrowedBooksCount();
        $sisaKuota = max(0, 3 - $activeBorrowedCount);

        // Hitung total denda berjalan + denda yang sudah tercatat
        $runningFines = 0;
        $lateLoansCount = 0;

        foreach ($activeLoans as $loan) {
            $fine = $loan->hitungDenda();
            if ($fine > 0) {
                $runningFines += $fine;
                $lateLoansCount++;
            }
        }

        $totalHistoricalFines = $user->loans()->where('status', 'terlambat')->sum('denda');

        // Rekomendasi / buku terbaru tersedia
        $featuredBooks = Book::with('category')
            ->available()
            ->latest()
            ->take(4)
            ->get();

        return view('anggota.dashboard', compact(
            'user',
            'activeLoans',
            'activeBorrowedCount',
            'sisaKuota',
            'runningFines',
            'lateLoansCount',
            'totalHistoricalFines',
            'featuredBooks'
        ));
    }
}
