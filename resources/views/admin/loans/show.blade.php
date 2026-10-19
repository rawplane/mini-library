<x-app-layout>
    <x-slot name="header">
        Detail Peminjaman: #LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Link -->
        <div>
            <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Peminjaman
            </a>
        </div>

        <!-- Status & Overview Card -->
        <x-card class="p-6 border border-gray-100 dark:border-gray-700/60 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700/60 pb-4">
                <div>
                    <span class="text-xs font-mono font-semibold text-indigo-600 dark:text-indigo-400">
                        #LN-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                        Informasi Transaksi Peminjaman
                    </h3>
                </div>
                <div>
                    <x-badge :variant="$loan->status" class="text-sm px-3 py-1">
                        Status: {{ ucfirst($loan->status) }}
                    </x-badge>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Peminjam</p>
                    <p class="font-bold text-gray-900 dark:text-white mt-1">{{ $loan->user->name ?? 'Anggota Dihapus' }}</p>
                    <p class="text-xs text-gray-500">{{ $loan->user->email ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Tanggal Pinjam</p>
                    <p class="font-bold text-gray-900 dark:text-white mt-1">{{ $loan->tanggal_pinjam->format('d F Y') }}</p>
                    <p class="text-xs text-gray-500">Durasi: 7 Hari</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Batas Jatuh Tempo</p>
                    <p class="font-bold {{ $loan->status !== 'dikembalikan' && $loan->tanggal_jatuh_tempo < now()->startOfDay() ? 'text-rose-600' : 'text-gray-900 dark:text-white' }} mt-1">
                        {{ $loan->tanggal_jatuh_tempo->format('d F Y') }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $loan->tanggal_kembali ? 'Dikembalikan: ' . $loan->tanggal_kembali->format('d F Y') : 'Belum Dikembalikan' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Denda Keterlambatan</p>
                    <p class="text-lg font-bold {{ ($loan->denda > 0 || $liveFine > 0) ? 'text-rose-600' : 'text-gray-900 dark:text-white' }} mt-1">
                        @if ($loan->denda > 0)
                            Rp {{ number_format($loan->denda, 0, ',', '.') }}
                        @elseif ($liveFine > 0)
                            Rp {{ number_format($liveFine, 0, ',', '.') }} <span class="text-xs font-normal text-rose-500">(Berjalan)</span>
                        @else
                            Rp 0
                        @endif
                    </p>
                    <p class="text-[11px] text-gray-400">Tarif Rp 1.000 / hari keterlambatan</p>
                </div>
            </div>

            <!-- List of Books Borrowed -->
            <div class="border-t border-gray-100 dark:border-gray-700/60 pt-6">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">
                    Daftar Buku yang Dipinjam ({{ $loan->books->count() }} Buku)
                </h4>

                <div class="divide-y divide-gray-100 dark:divide-gray-700/60 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    @foreach ($loan->books as $b)
                        <div class="p-4 flex items-center justify-between gap-4 bg-white dark:bg-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                    @if ($b->cover)
                                        <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ $b->judul }}</h5>
                                    <p class="text-xs text-gray-500">Penulis: {{ $b->penulis }} &bull; Penerbit: {{ $b->penerbit }} ({{ $b->tahun }})</p>
                                    <div class="mt-1">
                                        <x-badge variant="indigo" class="text-[10px]">
                                            {{ $b->category->nama ?? 'Umum' }}
                                        </x-badge>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-400">Stok Saat Ini:</span>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 block">{{ $b->stok }} eks</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Return Action if not returned -->
            @if ($loan->status !== 'dikembalikan')
                <div class="border-t border-gray-100 dark:border-gray-700/60 pt-6">
                    <form method="POST" action="{{ route('admin.loans.return', $loan) }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700">
                        @csrf
                        <div>
                            <h5 class="text-sm font-bold text-gray-900 dark:text-white">Proses Pengembalian Hari Ini</h5>
                            <p class="text-xs text-gray-500">Kembalikan semua stok buku dan rekam denda keterlambatan secara otomatis.</p>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <input type="hidden" name="tanggal_kembali" value="{{ now()->toDateString() }}">
                            <x-button type="submit" variant="success" size="md" class="w-full sm:w-auto">
                                Proses Pengembalian Sekarang
                            </x-button>
                        </div>
                    </form>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
