<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Perpustakaan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan statistik, tren peminjaman, dan aktivitas sirkulasi buku</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.loans.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Peminjaman
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Buku -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Judul Buku</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalBooks) }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ number_format($totalBookCopies) }} total eksemplar</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Total Anggota -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Anggota Terdaftar</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalMembers) }}</h3>
                <a href="{{ route('admin.members.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-0.5 inline-block">Kelola anggota &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Pinjaman Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pinjaman Aktif</p>
                <h3 class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ number_format($activeLoans) }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sedang dalam sirkulasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Pinjaman Terlambat -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pinjaman Terlambat</p>
                <h3 class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">{{ number_format($lateLoans) }}</h3>
                <p class="text-xs text-rose-500 dark:text-rose-400 mt-0.5">Perlu tindak lanjut</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Grafik Peminjaman 6 Bulan Terakhir & Buku Stok Menipis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Chart.js 6 Bulan -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tren Peminjaman Buku</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Statistik transaksi peminjaman dalam 6 bulan terakhir</p>
                </div>
                <div class="text-xs font-semibold px-3 py-1 bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 rounded-lg">
                    Total Denda: Rp {{ number_format($totalFines, 0, ',', '.') }}
                </div>
            </div>

            <div class="h-72 w-full relative">
                <canvas id="loansChart"></canvas>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Stok Menipis</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Buku dengan sisa stok &le; 2</p>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Semua</a>
            </div>

            @if($lowStockBooks->isEmpty())
                <div class="py-8 text-center text-sm text-gray-400">
                    Semua stok buku dalam jumlah aman (&gt; 2).
                </div>
            @else
                <div class="space-y-3">
                    @foreach($lowStockBooks as $book)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700/60">
                            <div class="min-w-0 pr-2">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $book->judul }}</h4>
                                <p class="text-xs text-gray-400">{{ $book->category->nama ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $book->stok === 0 ? 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400' }}">
                                    Stok: {{ $book->stok }}
                                </span>
                                <a href="{{ route('admin.books.edit', $book) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-white dark:hover:bg-gray-700" title="Edit stok">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Peminjaman Terbaru -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Peminjaman Terbaru</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Daftar transaksi sirkulasi terbaru</p>
            </div>
            <a href="{{ route('admin.loans.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                Lihat Semua Sirkulasi &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-gray-700/60">
                        <th class="py-3 px-4 font-semibold">Peminjam</th>
                        <th class="py-3 px-4 font-semibold">Buku Dipinjam</th>
                        <th class="py-3 px-4 font-semibold">Tgl Pinjam</th>
                        <th class="py-3 px-4 font-semibold">Jatuh Tempo</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($recentLoans as $loan)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $loan->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $loan->user->email ?? '' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $loan->books->pluck('judul')->join(', ') }}</span>
                                <span class="text-xs text-gray-400 block">({{ $loan->books->count() }} buku)</span>
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="{{ $loan->status === 'terlambat' || ($loan->status === 'dipinjam' && \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->isPast()) ? 'text-rose-600 dark:text-rose-400 font-semibold' : 'text-gray-600 dark:text-gray-300' }}">
                                    {{ \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <x-badge :status="$loan->status" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.loans.show', $loan) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-sm">Belum ada transaksi peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Chart.js Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('loansChart');
            if (!ctx) return;

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#f1f5f9';

            new window.Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 10,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' buku dipinjam';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: textColor,
                                font: { size: 11 }
                            },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
