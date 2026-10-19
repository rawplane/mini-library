<x-app-layout>
    <x-slot name="header">
        Detail Anggota: {{ $member->name }}
    </x-slot>

    <div class="space-y-6">
        <!-- Back Link & Action Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Anggota
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.loans.create') }}?user_id={{ $member->id }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Catat Pinjaman Baru
                </a>
            </div>
        </div>

        <!-- Top Profile & Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Member Card -->
            <x-card class="md:col-span-1 p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 dark:bg-indigo-500 text-white font-bold text-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20 mb-3">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ $member->name }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                <div class="mt-2">
                    <x-badge variant="indigo">Anggota Aktif</x-badge>
                </div>
                <p class="text-[11px] text-gray-400 mt-4">Bergabung sejak {{ $member->created_at->format('d F Y') }}</p>
            </x-card>

            <!-- Stat 1: Sisa Kuota -->
            <x-card class="p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Buku Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $activeBorrowedBooksCount }} <span class="text-xs text-gray-400 font-normal">/ 3 maks</span></p>
                </div>
            </x-card>

            <!-- Stat 2: Transaksi Aktif -->
            <x-card class="p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Peminjaman Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $activeLoansCount }}</p>
                </div>
            </x-card>

            <!-- Stat 3: Total Denda -->
            <x-card class="p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Denda Tercatat</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">Rp {{ number_format($totalFines, 0, ',', '.') }}</p>
                </div>
            </x-card>
        </div>

        <!-- Loan History Section -->
        <div class="space-y-4">
            <h4 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Riwayat Peminjaman Buku
            </h4>

            <x-card class="overflow-hidden p-0 border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">ID Pinjam</th>
                                <th class="px-6 py-4">Buku yang Dipinjam</th>
                                <th class="px-6 py-4">Tanggal Pinjam</th>
                                <th class="px-6 py-4">Jatuh Tempo</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Denda</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse ($loans as $loan)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                        #LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @foreach ($loan->books as $b)
                                                <div class="text-xs font-medium text-gray-900 dark:text-white flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                    <span>{{ $b->judul }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-700 dark:text-gray-300">
                                        {{ $loan->tanggal_pinjam->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs {{ $loan->status !== 'dikembalikan' && $loan->tanggal_jatuh_tempo < now() ? 'text-rose-600 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $loan->tanggal_jatuh_tempo->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-badge :variant="$loan->status">
                                            {{ ucfirst($loan->status) }}
                                        </x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold {{ $loan->denda > 0 ? 'text-rose-600' : 'text-gray-500' }}">
                                        {{ $loan->denda > 0 ? 'Rp ' . number_format($loan->denda, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.loans.show', $loan) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-xs text-gray-400">
                                        Anggota ini belum pernah meminjam buku.
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
    </div>
</x-app-layout>
