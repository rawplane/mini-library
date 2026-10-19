<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Tampilkan katalog buku admin dengan pencarian dan filter kategori.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $books = Book::with('category')
            ->search($search)
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('nama')->get();

        return view('admin.books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    /**
     * Tampilkan form tambah buku baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('nama')->get();

        return view('admin.books.create', compact('categories'));
    }

    /**
     * Simpan data buku baru ke database beserta file cover.
     */
    public function store(BookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit buku.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('nama')->get();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Perbarui data buku dan perbarui file cover bila diunggah baru.
     */
    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            // Hapus file cover lama jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Hapus buku dari katalog jika tidak sedang dipinjam secara aktif.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Cegah hapus bila buku masih berstatus pinjam aktif / terlambat
        $hasActiveLoans = $book->loans()->whereIn('status', ['dipinjam', 'terlambat'])->exists();

        if ($hasActiveLoans) {
            return redirect()->route('admin.books.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih tercatat dalam peminjaman aktif.');
        }

        // Hapus file cover dari storage
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus dari sistem.');
    }
}
