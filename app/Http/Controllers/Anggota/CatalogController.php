<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Requests\Anggota\BorrowRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Tampilkan katalog buku dengan pencarian, filter kategori, dan paginasi.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $books = Book::with('category')
            ->search($search)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->orderByRaw('stok > 0 DESC')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::withCount('books')->orderBy('nama')->get();

        $user = auth()->user();
        $activeBorrowedCount = $user->activeBorrowedBooksCount();
        $sisaKuota = max(0, 3 - $activeBorrowedCount);

        return view('anggota.catalog.index', compact(
            'books',
            'categories',
            'search',
            'categoryId',
            'activeBorrowedCount',
            'sisaKuota'
        ));
    }

    /**
     * Ajukan peminjaman buku oleh anggota (DB::transaction).
     */
    public function borrow(BorrowRequest $request): RedirectResponse
    {
        $user = auth()->user();

        try {
            DB::transaction(function () use ($user, $request) {
                $currentActiveCount = $user->activeBorrowedBooksCount();
                $requestedCount = count($request->book_ids);

                if (($currentActiveCount + $requestedCount) > 3) {
                    $sisaSlot = max(0, 3 - $currentActiveCount);
                    throw new \RuntimeException(
                        "Anda telah meminjam {$currentActiveCount} buku aktif. Kuota tersisa hanya {$sisaSlot} buku (Maksimal 3 buku)."
                    );
                }

                $books = Book::whereIn('id', $request->book_ids)->lockForUpdate()->get();

                foreach ($books as $book) {
                    if ($book->stok <= 0) {
                        throw new \RuntimeException("Buku '{$book->judul}' sedang tidak tersedia / stok habis.");
                    }
                }

                $loan = Loan::create([
                    'user_id' => $user->id,
                    'tanggal_pinjam' => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
                    'status' => 'dipinjam',
                    'denda' => 0,
                ]);

                foreach ($books as $book) {
                    $book->decrement('stok');

                    LoanDetail::create([
                        'loan_id' => $loan->id,
                        'book_id' => $book->id,
                    ]);
                }
            });

            return redirect()->route('anggota.loans.index')
                ->with('success', 'Pengajuan peminjaman buku berhasil diproses! Durasi pinjam 7 hari.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }
}
