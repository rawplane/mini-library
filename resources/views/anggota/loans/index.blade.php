<x-app-layout>
    <x-slot name="header">
        Riwayat Peminjaman Saya
    </x-slot>

    <div class="space-y-6">
        <!-- Top Controls & Status Tabs -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-1.5 p-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm text-xs font-semibold">
                <a
                    href="{{ route('anggota.loans.index') }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Semua
                </a>
                <a
                    href="{{ route('anggota.loans.index', ['status' => 'aktif']) }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ $status === 'aktif' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Sedang Dipinjam
                </a>
                <a
                    href="{{ route('anggota.loans.index', ['status' => 'dikembalikan']) }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ $status === 'dikembalikan' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Selesai
                </a>
            </div>

            <div>
                <a href="{{ route('anggota.catalog.index') }}">
                    <x-button variant="primary" size="sm" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Pinjam Buku Lain</span>
                    </x-button>
                </a>
            </div>
        </div>

        <!-- Loans Table Card -->
        <x-card class="overflow-hidden p-0 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">ID Transaksi</th>
                            <th class="px-6 py-4">Buku yang Dipinjam</th>
                            <th class="px-6 py-4">Tanggal Pinjam</th>
                            <th class="px-6 py-4">Batas Pengembalian</th>
                            <th class="px-6 py-4">Tanggal Dikembalikan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse ($loans as $loan)
                            @php
                                $isLateUnreturned = in_array($loan->status, ['dipinjam', 'terlambat']) && ! $loan->tanggal_kembali && $loan->tanggal_jatuh_tempo < now()->startOfDay();
                                $effectiveStatus = $isLateUnreturned ? 'terlambat' : $loan->status;
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                    #LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @foreach ($loan->books as $b)
                                            <div class="text-xs font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                <span>{{ $b->judul }}</span>
                                                <span class="text-[10px] text-gray-400 font-normal">({{ $b->category->nama ?? 'Umum' }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-700 dark:text-gray-300">
                                    {{ $loan->tanggal_pinjam->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-xs {{ $isLateUnreturned ? 'text-rose-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $loan->tanggal_jatuh_tempo->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d M Y') : 'Belum Dikembalikan' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-badge :variant="$effectiveStatus">
                                        {{ ucfirst($effectiveStatus) }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-semibold">
                                    @if ($loan->denda > 0)
                                        <span class="text-rose-600">Rp {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                    @elseif ($isLateUnreturned)
                                        @php $liveFine = $loan->hitungDenda(now()); @endphp
                                        <span class="text-rose-500 text-[11px]" title="Denda berjalan per hari ini">
                                            Rp {{ number_format($liveFine, 0, ',', '.') }} (Berjalan)
                                        </span>
                                    @else
                                        <span class="text-gray-400">Rp 0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <p class="text-base font-medium text-gray-700 dark:text-gray-300">Belum ada riwayat peminjaman</p>
                                        <p class="text-xs text-gray-400 mt-1">Pilih dan ajukan peminjaman buku dari katalog perpustakaan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($loans->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $loans->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
