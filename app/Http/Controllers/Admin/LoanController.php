<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoanRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Tampilkan daftar transaksi peminjaman buku.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $loans = Loan::with(['user', 'books.category'])
            ->when($status, function ($query, $status) {
                if ($status === 'aktif') {
                    $query->whereIn('status', ['dipinjam', 'terlambat']);
                } elseif (in_array($status, ['dipinjam', 'dikembalikan', 'terlambat'])) {
                    $query->where('status', $status);
                }
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                      ->orWhereHas('books', fn ($b) => $b->where('judul', 'like', "%{$search}%"));
                });
            })
            ->latest('tanggal_pinjam')
            ->paginate(10)
            ->withQueryString();

        return view('admin.loans.index', compact('loans', 'status', 'search'));
    }

    /**
     * Form pencatatan peminjaman baru oleh Admin.
     */
    public function create(): View
    {
        $members = User::where('role', 'anggota')->orderBy('name')->get();
        $books = Book::with('category')->where('stok', '>', 0)->orderBy('judul')->get();

        $defaultPinjam = now()->toDateString();
        $defaultJatuhTempo = now()->addDays(7)->toDateString();

        return view('admin.loans.create', compact('members', 'books', 'defaultPinjam', 'defaultJatuhTempo'));
    }

    /**
     * Simpan transaksi peminjaman baru (DB::transaction).
     */
    public function store(LoanRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::findOrFail($request->user_id);

                // Validasi batas maksimal 3 buku aktif per anggota
                $currentActiveCount = $user->activeBorrowedBooksCount();
                $requestedCount = count($request->book_ids);

                if (($currentActiveCount + $requestedCount) > 3) {
                    $sisaSlot = max(0, 3 - $currentActiveCount);
                    throw new \RuntimeException(
                        "Anggota ini telah meminjam {$currentActiveCount} buku aktif. Kuota tersisa hanya {$sisaSlot} buku (Maksimal 3 buku)."
                    );
                }

                // Ambil buku dengan lockForUpdate untuk mencegah race condition stok
                $books = Book::whereIn('id', $request->book_ids)->lockForUpdate()->get();

                foreach ($books as $book) {
                    if ($book->stok <= 0) {
                        throw new \RuntimeException("Buku '{$book->judul}' sedang kehabisan stok.");
                    }
                }

                // Buat record peminjaman
                $loan = Loan::create([
                    'user_id' => $user->id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                    'status' => 'dipinjam',
                    'denda' => 0,
                ]);

                // Kurangi stok dan simpan detail buku
                foreach ($books as $book) {
                    $book->decrement('stok');

                    LoanDetail::create([
                        'loan_id' => $loan->id,
                        'book_id' => $book->id,
                    ]);
                }
            });

            return redirect()->route('admin.loans.index')
                ->with('success', 'Transaksi peminjaman buku berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses peminjaman.');
        }
    }

    /**
     * Tampilkan detail peminjaman buku.
     */
    public function show(Loan $loan): View
    {
        $loan->load(['user', 'books.category']);

        $liveFine = 0;
        if (in_array($loan->status, ['dipinjam', 'terlambat']) && ! $loan->tanggal_kembali) {
            $liveFine = $loan->hitungDenda(now());
        }

        return view('admin.loans.show', compact('loan', 'liveFine'));
    }

    /**
     * Catat pengembalian buku dan kalkulasi denda otomatis (DB::transaction).
     */
    public function returnBook(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->status === 'dikembalikan') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        $tanggalKembaliStr = $request->input('tanggal_kembali', now()->toDateString());
        $tanggalKembali = Carbon::parse($tanggalKembaliStr);

        try {
            DB::transaction(function () use ($loan, $tanggalKembali) {
                // Hitung denda keterlambatan (Rp1.000 / hari)
                $denda = $loan->hitungDenda($tanggalKembali);
                $status = $denda > 0 ? 'terlambat' : 'dikembalikan';

                $loan->update([
                    'tanggal_kembali' => $tanggalKembali->toDateString(),
                    'status' => $status,
                    'denda' => $denda,
                ]);

                // Kembalikan stok buku yang dipinjam
                foreach ($loan->books as $book) {
                    $book->increment('stok');
                }
            });

            $pesan = 'Pengembalian buku berhasil diproses.';
            if ($loan->denda > 0) {
                $pesan .= ' Terdapat denda keterlambatan sebesar Rp ' . number_format($loan->denda, 0, ',', '.');
            }

            return redirect()->route('admin.loans.index')->with('success', $pesan);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}
