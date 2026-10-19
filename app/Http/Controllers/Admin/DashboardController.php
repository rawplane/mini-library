<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // 1. Kartu statistik
        $totalBooks = Book::count();
        $totalBookCopies = Book::sum('stok');
        $totalMembers = User::where('role', 'anggota')->count();
        
        $activeLoans = Loan::whereIn('status', ['dipinjam', 'terlambat'])->count();
        
        $lateLoans = Loan::where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'dipinjam')
                  ->where('tanggal_jatuh_tempo', '<', now()->toDateString());
            })
            ->count();

        $totalFines = Loan::sum('denda');

        // 2. Data grafik peminjaman 6 bulan terakhir
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M Y');

            $count = Loan::whereYear('tanggal_pinjam', $month->year)
                ->whereMonth('tanggal_pinjam', $month->month)
                ->count();

            $chartData[] = $count;
        }

        // 3. Peminjaman terbaru & buku dengan stok menipis
        $recentLoans = Loan::with(['user', 'books'])
            ->latest('tanggal_pinjam')
            ->take(5)
            ->get();

        $lowStockBooks = Book::with('category')
            ->where('stok', '<=', 2)
            ->orderBy('stok')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalBookCopies',
            'totalMembers',
            'activeLoans',
            'lateLoans',
            'totalFines',
            'chartLabels',
            'chartData',
            'recentLoans',
            'lowStockBooks'
        ));
    }
}
