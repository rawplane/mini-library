<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());
        $status = $request->query('status');

        $query = Loan::with(['user', 'books.category'])
            ->whereDate('tanggal_pinjam', '>=', $startDate)
            ->whereDate('tanggal_pinjam', '<=', $endDate)
            ->when($status && $status !== 'semua', fn ($q) => $q->where('status', $status));

        $loans = (clone $query)->latest('tanggal_pinjam')->paginate(15)->withQueryString();

        // Ringkasan periode
        $totalLoans = (clone $query)->count();
        $totalFines = (clone $query)->sum('denda');
        $returnedCount = (clone $query)->whereIn('status', ['dikembalikan', 'terlambat'])->whereNotNull('tanggal_kembali')->count();

        return view('admin.reports.index', compact(
            'loans',
            'startDate',
            'endDate',
            'status',
            'totalLoans',
            'totalFines',
            'returnedCount'
        ));
    }

    public function exportPdf(Request $request): Response
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());
        $status = $request->query('status');

        $query = Loan::with(['user', 'books.category'])
            ->whereDate('tanggal_pinjam', '>=', $startDate)
            ->whereDate('tanggal_pinjam', '<=', $endDate)
            ->when($status && $status !== 'semua', fn ($q) => $q->where('status', $status));

        $loans = $query->orderBy('tanggal_pinjam')->get();

        $totalLoans = $loans->count();
        $totalFines = $loans->sum('denda');
        $totalBooksBorrowed = $loans->reduce(fn ($carry, $loan) => $carry + $loan->books->count(), 0);

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'loans',
            'startDate',
            'endDate',
            'status',
            'totalLoans',
            'totalFines',
            'totalBooksBorrowed'
        ))->setPaper('a4', 'landscape');

        $filename = 'laporan-peminjaman-' . Carbon::parse($startDate)->format('Ymd') . '-' . Carbon::parse($endDate)->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
