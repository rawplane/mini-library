<x-app-layout>
    <x-slot name="header">
        Informasi Denda Keterlambatan
    </x-slot>

    <div class="space-y-6">
        <!-- Top Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Stat 1: Denda Berjalan -->
            <x-card class="p-6 border border-rose-100 dark:border-rose-950/60 shadow-sm flex items-center gap-4 bg-gradient-to-br from-white to-rose-50/30 dark:from-gray-800 dark:to-rose-950/20">
                <div class="p-3.5 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Denda Berjalan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        Rp {{ number_format($totalLiveFine, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $activeLoans->count() }} transaksi belum dikembalikan</p>
                </div>
            </x-card>

            <!-- Stat 2: Denda Tercatat Selesai -->
            <x-card class="p-6 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4">
                <div class="p-3.5 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Riwayat Denda Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        Rp {{ number_format($totalCompletedFine, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $completedFinesLoans->count() }} transaksi keterlambatan</p>
                </div>
            </x-card>

            <!-- Stat 3: Kebijakan Denda -->
            <x-card class="p-6 border border-indigo-100 dark:border-indigo-950/60 shadow-sm flex items-center gap-4 bg-gradient-to-br from-white to-indigo-50/30 dark:from-gray-800 dark:to-indigo-950/20">
                <div class="p-3.5 rounded-2xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Tarif Denda Perpustakaan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp 1.000</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Per hari keterlambatan per transaksi</p>
                </div>
            </x-card>
        </div>

        <!-- Section 1: Pinjaman Terlambat Berjalan -->
        <div class="space-y-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                Pinjaman Melewati Jatuh Tempo (Denda Masih Berjalan)
            </h3>

            <x-card class="overflow-hidden p-0 border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">ID Transaksi</th>
                                <th class="px-6 py-4">Buku yang Dipinjam</th>
                                <th class="px-6 py-4">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-center">Hari Keterlambatan</th>
                                <th class="px-6 py-4 text-right">Estimasi Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse ($activeLoans as $loan)
                                <tr class="hover:bg-rose-50/20 dark:hover:bg-rose-950/10 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                        #LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @foreach ($loan->books as $b)
                                                <div class="text-xs font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    <span>{{ $b->judul }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-rose-600">
                                        {{ $loan->tanggal_jatuh_tempo->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs font-bold text-rose-600">
                                        {{ $loan->days_late }} Hari
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-sm text-rose-600">
                                        Rp {{ number_format($loan->live_denda, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-emerald-600 dark:text-emerald-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="font-bold text-sm">Tidak ada denda yang sedang berjalan!</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Semua buku yang Anda pinjam masih dalam batas durasi peminjaman.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <!-- Section 2: Riwayat Keterlambatan yang Telah Dikembalikan -->
        <div class="space-y-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                Riwayat Keterlambatan yang Telah Selesai / Dikembalikan
            </h3>

            <x-card class="overflow-hidden p-0 border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">ID Transaksi</th>
                                <th class="px-6 py-4">Buku</th>
                                <th class="px-6 py-4">Jatuh Tempo</th>
                                <th class="px-6 py-4">Tanggal Pengembalian</th>
                                <th class="px-6 py-4 text-right">Denda Terhitung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse ($completedFinesLoans as $loan)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                        #LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-gray-800 dark:text-gray-200">
                                        @foreach ($loan->books as $b)
                                            <div>{{ $b->judul }}</div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $loan->tanggal_jatuh_tempo->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-800 dark:text-gray-200 font-medium">
                                        {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-bold text-rose-600">
                                        Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-xs text-gray-400">
                                        Tidak ada riwayat denda selesai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
