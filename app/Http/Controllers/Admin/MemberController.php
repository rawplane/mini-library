<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemberRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Tampilkan daftar anggota dengan filter pencarian dan jumlah peminjaman.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $members = User::where('role', 'anggota')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount([
                'loans as total_loans_count',
                'loans as active_loans_count' => function ($query) {
                    $query->whereIn('status', ['dipinjam', 'terlambat']);
                },
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.members.index', compact('members', 'search'));
    }

    /**
     * Simpan data anggota baru.
     */
    public function store(MemberRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota perpustakaan berhasil didaftarkan.');
    }

    /**
     * Tampilkan detail anggota beserta riwayat peminjamannya.
     */
    public function show(User $member): View
    {
        if (! $member->isAnggota()) {
            abort(404);
        }

        $loans = $member->loans()
            ->with('books')
            ->latest()
            ->paginate(10);

        $activeLoansCount = $member->loans()->whereIn('status', ['dipinjam', 'terlambat'])->count();
        $totalFines = $member->loans()->sum('denda');
        $activeBorrowedBooksCount = $member->activeBorrowedBooksCount();

        return view('admin.members.show', compact('member', 'loans', 'activeLoansCount', 'totalFines', 'activeBorrowedBooksCount'));
    }

    /**
     * Perbarui data anggota.
     */
    public function update(MemberRequest $request, User $member): RedirectResponse
    {
        if (! $member->isAnggota()) {
            abort(404);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('admin.members.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Hapus anggota (hanya jika tidak memiliki pinjaman aktif).
     */
    public function destroy(User $member): RedirectResponse
    {
        if (! $member->isAnggota()) {
            abort(404);
        }

        $hasActiveLoans = $member->loans()
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->exists();

        if ($hasActiveLoans) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena masih memiliki tanggungan peminjaman aktif.');
        }

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
