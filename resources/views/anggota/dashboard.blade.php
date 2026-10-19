<x-app-layout>
    <x-slot name="title">Dashboard Anggota</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat Datang, {{ $user->name }}!</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pantau pinjaman buku, batas waktu pengembalian, dan temukan bacaan baru.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('anggota.catalog.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Buka Katalog Buku
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Ringkasan Kartu Anggota -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <!-- Kuota Pinjam -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sisa Kuota Pinjam</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <h3 class="text-3xl font-bold {{ $sisaKuota > 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $sisaKuota }}
                    </h3>
                    <span class="text-xs text-gray-400">/ 3 buku maksimal</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ $activeBorrowedCount }} buku sedang dipinjam
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Pinjaman Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pinjaman Aktif</p>
                <h3 class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $activeLoans->count() }}</h3>
                <a href="{{ route('anggota.loans.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">
                    Lihat riwayat lengkap &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Denda Berjalan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Denda Berjalan</p>
                <h3 class="text-2xl font-bold {{ $runningFines > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} mt-1">
                    Rp {{ number_format($runningFines, 0, ',', '.') }}
                </h3>
                <a href="{{ route('anggota.fines.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">
                    Detail denda &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl {{ $runningFines > 0 ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' : 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Alert Keterlambatan jika ada -->
    @if($runningFines > 0)
        <div class="mb-8 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <h4 class="text-sm font-bold text-rose-900 dark:text-rose-200">Perhatian: Anda memiliki pinjaman melewati jatuh tempo!</h4>
                <p class="text-xs text-rose-700 dark:text-rose-300 mt-0.5">Segera kembalikan buku ke petugas perpustakaan. Tarif denda adalah Rp 1.000 per hari keterlambatan.</p>
            </div>
        </div>
    @endif

    <!-- Pinjaman Aktif Saat Ini -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Buku yang Sedang Dipinjam</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Daftar buku yang wajib Anda kembalikan ke perpustakaan</p>
            </div>
            <a href="{{ route('anggota.loans.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                Lihat Riwayat &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-gray-700/60">
                        <th class="py-3 px-4 font-semibold">Buku Dipinjam</th>
                        <th class="py-3 px-4 font-semibold">Tgl Pinjam</th>
                        <th class="py-3 px-4 font-semibold">Jatuh Tempo</th>
                        <th class="py-3 px-4 font-semibold">Status / Sisa Waktu</th>
                        <th class="py-3 px-4 font-semibold text-right">Denda Berjalan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($activeLoans as $loan)
                        @php
                            $fine = $loan->hitungDenda();
                            $tempo = \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->startOfDay();
                            $now = now()->startOfDay();
                            $isLate = $now->greaterThan($tempo);
                            $diffDays = $now->diffInDays($tempo, false);
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="py-3 px-4">
                                <ul class="space-y-1">
                                    @foreach($loan->books as $b)
                                        <li class="font-medium text-gray-900 dark:text-white flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 inline-block"></span>
                                            {{ $b->judul }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="{{ $isLate ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-800 dark:text-gray-200' }}">
                                    {{ $tempo->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($isLate)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400">
                                        Terlambat {{ abs($diffDays) }} hari
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400">
                                        Sisa {{ $diffDays }} hari lagi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if($fine > 0)
                                    <span class="font-bold text-rose-600 dark:text-rose-400">
                                        Rp {{ number_format($fine, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                                Saat ini Anda tidak memiliki peminjaman buku yang aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rekomendasi Buku Tersedia -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Buku Tersedia untuk Dipinjam</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Pilihan buku koleksi terbaru yang siap Anda baca</p>
            </div>
            <a href="{{ route('anggota.catalog.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                Buka Semua Katalog &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($featuredBooks as $book)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <!-- Cover -->
                        <div class="h-44 bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center overflow-hidden relative">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="text-gray-400 flex flex-col items-center gap-1">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span class="text-[10px]">No Cover</span>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/90 dark:bg-gray-900/90 text-gray-800 dark:text-gray-200 shadow-sm backdrop-blur-sm">
                                Stok: {{ $book->stok }}
                            </span>
                        </div>

                        <div class="p-4">
                            <span class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block mb-1">
                                {{ $book->category->nama ?? '-' }}
                            </span>
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $book->judul }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">
                                {{ $book->penulis }} ({{ $book->tahun }})
                            </p>
                        </div>
                    </div>

                    <div class="p-4 pt-0">
                        <a href="{{ route('anggota.catalog.index', ['search' => $book->judul]) }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-xl text-xs font-semibold transition-colors">
                            Pinjam via Katalog
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
