<x-app-layout>
    <x-slot name="title">Laporan Peminjaman</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Peminjaman & Sirkulasi</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Filter transaksi peminjaman berdasarkan rentang tanggal dan unduh laporan resmi format PDF.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => $status]) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ekspor PDF
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Filter Bar Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status Peminjaman</label>
                <select name="status" class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="semua" {{ $status === 'semua' || !$status ? 'selected' : '' }}>Semua Status</option>
                    <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Laporan
                </button>
                <a href="{{ route('admin.reports.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-medium transition-colors" title="Reset filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Periode Laporan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalLoans) }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">Transaksi peminjaman dalam periode</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sudah Dikembalikan</p>
            <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($returnedCount) }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">Sirkulasi selesai diproses</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Denda Terkumpul</p>
            <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">Rp {{ number_format($totalFines, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">Dari denda keterlambatan buku</p>
        </div>
    </div>

    <!-- Tabel Data Laporan -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Rincian Transaksi Peminjaman</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</p>
            </div>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                Menampilkan {{ $loans->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-gray-700/60">
                        <th class="py-3 px-4 font-semibold">No</th>
                        <th class="py-3 px-4 font-semibold">Peminjam</th>
                        <th class="py-3 px-4 font-semibold">Buku</th>
                        <th class="py-3 px-4 font-semibold">Tgl Pinjam</th>
                        <th class="py-3 px-4 font-semibold">Jatuh Tempo</th>
                        <th class="py-3 px-4 font-semibold">Tgl Kembali</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($loans as $index => $loan)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="py-3 px-4 text-gray-400 text-xs">
                                {{ $loans->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $loan->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $loan->user->email ?? '' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <ul class="list-disc list-inside text-xs text-gray-800 dark:text-gray-200">
                                    @foreach($loan->books as $b)
                                        <li class="truncate max-w-xs">{{ $b->judul }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300 text-xs">
                                {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300 text-xs">
                                {{ \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->translatedFormat('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300 text-xs">
                                {{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->translatedFormat('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <x-badge :status="$loan->status" />
                            </td>
                            <td class="py-3 px-4 text-right font-semibold {{ $loan->denda > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500' }}">
                                Rp {{ number_format($loan->denda, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400 text-sm">
                                Tidak ada data peminjaman yang cocok pada rentang tanggal dan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loans->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700/60">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
