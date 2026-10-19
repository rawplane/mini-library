<x-app-layout>
    <x-slot name="header">
        Katalog Buku Perpustakaan
    </x-slot>

    <div
        x-data="{
            borrowModalOpen: false,
            borrowBookId: null,
            borrowBookTitle: '',
            borrowBookAuthor: '',
            borrowBookCategory: '',
            sisaKuota: {{ $sisaKuota }},
            openBorrow(id, title, author, category) {
                if (this.sisaKuota <= 0) {
                    alert('Batas peminjaman Anda sudah mencapai maksimal 3 buku aktif. Kembalikan buku yang dipinjam terlebih dahulu.');
                    return;
                }
                this.borrowBookId = id;
                this.borrowBookTitle = title;
                this.borrowBookAuthor = author;
                this.borrowBookCategory = category;
                this.borrowModalOpen = true;
            }
        }"
        class="space-y-6"
    >
        <!-- Member Quota Banner -->
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-800 text-white shadow-md">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/20 text-white mb-2">
                        Perpustakaan Mini &bull; Peminjaman Mandiri
                    </span>
                    <h2 class="text-xl font-bold">Halo, {{ auth()->user()->name }}!</h2>
                    <p class="text-sm text-indigo-100 mt-1 max-w-xl">
                        Temukan koleksi buku favoritmu. Peminjaman berlaku selama 7 hari kalender dengan batas kuota 3 buku aktif.
                    </p>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/10">
                    <div class="text-center">
                        <span class="text-xs text-indigo-200 block">Buku Sedang Dipinjam</span>
                        <span class="text-2xl font-bold">{{ $activeBorrowedCount }} / 3</span>
                    </div>
                    <div class="h-8 w-px bg-white/20"></div>
                    <div class="text-center">
                        <span class="text-xs text-indigo-200 block">Sisa Kuota Pinjam</span>
                        <span class="text-2xl font-bold text-emerald-300">{{ $sisaKuota }}</span>
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/5 pointer-events-none"></div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
                <a
                    href="{{ route('anggota.catalog.index', array_filter(['search' => $search])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ empty($categoryId) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}"
                >
                    Semua Kategori
                </a>
                @foreach ($categories as $cat)
                    <a
                        href="{{ route('anggota.catalog.index', array_filter(['category_id' => $cat->id, 'search' => $search])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $categoryId == $cat->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}"
                    >
                        {{ $cat->nama }} <span class="text-[10px] opacity-75">({{ $cat->books_count }})</span>
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('anggota.catalog.index') }}" class="flex items-center gap-2 max-w-sm w-full">
                @if ($categoryId)
                    <input type="hidden" name="category_id" value="{{ $categoryId }}">
                @endif
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari judul, penulis..."
                        class="w-full pl-9 pr-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white placeholder-gray-400 shadow-sm"
                    >
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <x-button type="submit" variant="secondary" size="sm">
                    Cari
                </x-button>
            </form>
        </div>

        <!-- Book Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($books as $book)
                <div class="flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                    <!-- Cover Container -->
                    <div class="relative aspect-[3/4] bg-slate-100 dark:bg-slate-700/50 overflow-hidden flex items-center justify-center">
                        @if ($book->cover)
                            <img
                                src="{{ asset('storage/' . $book->cover) }}"
                                alt="{{ $book->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        @else
                            <div class="flex flex-col items-center justify-center text-slate-400 p-4 text-center">
                                <svg class="w-12 h-12 mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="text-xs font-semibold text-slate-500">{{ $book->category->nama ?? 'Buku' }}</span>
                            </div>
                        @endif

                        <!-- Stock Badge Overlay -->
                        <div class="absolute top-3 right-3">
                            @if ($book->stok > 0)
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-emerald-500/90 backdrop-blur-md text-white shadow-sm">
                                    Tersedia ({{ $book->stok }})
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-rose-500/90 backdrop-blur-md text-white shadow-sm">
                                    Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Book Info Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 block mb-1">
                                {{ $book->category->nama ?? 'Umum' }}
                            </span>
                            <h3 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug" title="{{ $book->judul }}">
                                {{ $book->judul }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                                {{ $book->penulis }}
                            </p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                {{ $book->penerbit }} &bull; {{ $book->tahun }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60">
                            @if ($book->stok > 0)
                                <button
                                    type="button"
                                    @click="openBorrow({{ $book->id }}, '{{ addslashes($book->judul) }}', '{{ addslashes($book->penulis) }}', '{{ addslashes($book->category->nama ?? '') }}')"
                                    class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-colors flex items-center justify-center gap-1.5 shadow-sm"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Ajukan Pinjam</span>
                                </button>
                            @else
                                <button
                                    type="button"
                                    disabled
                                    class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed text-center"
                                >
                                    Stok Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-base font-semibold text-gray-700 dark:text-gray-300">Tidak ada buku ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau ganti filter kategori.</p>
                </div>
            @endforelse
        </div>

        @if ($books->hasPages())
            <div class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                {{ $books->links() }}
            </div>
        @endif

        <!-- Modal Konfirmasi Peminjaman Buku -->
        <x-modal name="borrow-confirm-modal" show="borrowModalOpen">
            <form method="POST" action="{{ route('anggota.catalog.borrow') }}" class="p-6">
                @csrf
                <input type="hidden" name="book_ids[]" :value="borrowBookId">

                <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400 mb-4">
                    <div class="p-2.5 bg-indigo-100 dark:bg-indigo-950/60 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Konfirmasi Peminjaman
                        </h3>
                        <p class="text-xs text-gray-500">Durasi peminjaman standar 7 hari</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 space-y-2 mb-4">
                    <p class="text-xs text-gray-500">Judul Buku:</p>
                    <p class="font-bold text-sm text-gray-900 dark:text-white" x-text="borrowBookTitle"></p>
                    <p class="text-xs text-gray-500">Penulis: <span class="text-gray-800 dark:text-gray-200 font-medium" x-text="borrowBookAuthor"></span></p>
                </div>

                <div class="space-y-2 text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex items-center justify-between">
                        <span>Tanggal Mulai Pinjam:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ now()->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Batas Waktu Pengembalian:</span>
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ now()->addDays(7)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Denda jika terlambat:</span>
                        <span class="font-semibold text-rose-600">Rp 1.000 / hari</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" @click="borrowModalOpen = false" variant="secondary">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Setujui & Pinjam Buku
                    </x-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
