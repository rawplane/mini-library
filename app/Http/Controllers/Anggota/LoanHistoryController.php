<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanHistoryController extends Controller
{
    /**
     * Tampilkan riwayat peminjaman buku milik anggota.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $loans = auth()->user()->loans()
            ->with('books.category')
            ->when($status, function ($query, $status) {
                if ($status === 'aktif') {
                    $query->whereIn('status', ['dipinjam', 'terlambat']);
                } elseif (in_array($status, ['dipinjam', 'dikembalikan', 'terlambat'])) {
                    $query->where('status', $status);
                }
            })
            ->latest('tanggal_pinjam')
            ->paginate(10)
            ->withQueryString();

        return view('anggota.loans.index', compact('loans', 'status'));
    }

    /**
     * Tampilkan denda yang berjalan dan histori denda keterlambatan.
     */
    public function fines(): View
    {
        $user = auth()->user();

        // Pinjaman aktif yang sudah melewati jatuh tempo (denda berjalan)
        $activeLoans = $user->loans()
            ->with('books.category')
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->get()
            ->map(function ($loan) {
                $loan->live_denda = $loan->hitungDenda(now());
                $loan->days_late = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->startOfDay());
                return $loan;
            });

        $totalLiveFine = $activeLoans->sum('live_denda');

        // Pinjaman yang sudah dikembalikan dengan denda
        $completedFinesLoans = $user->loans()
            ->with('books.category')
            ->where('status', 'terlambat')
            ->whereNotNull('tanggal_kembali')
            ->latest('tanggal_kembali')
            ->get();

        $totalCompletedFine = $completedFinesLoans->sum('denda');

        return view('anggota.loans.fines', compact(
            'activeLoans',
            'totalLiveFine',
            'completedFinesLoans',
            'totalCompletedFine'
        ));
    }
}
