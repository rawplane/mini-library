<x-app-layout>
    <x-slot name="header">
        Catat Peminjaman Buku Baru
    </x-slot>

    <div
        x-data="{
            selectedBooks: [],
            maxBooks: 3,
            toggleBook(id) {
                const index = this.selectedBooks.indexOf(id);
                if (index > -1) {
                    this.selectedBooks.splice(index, 1);
                } else {
                    if (this.selectedBooks.length >= this.maxBooks) {
                        alert('Maksimal 3 buku dalam satu transaksi peminjaman.');
                        return;
                    }
                    this.selectedBooks.push(id);
                }
            }
        }"
        class="max-w-4xl mx-auto space-y-6"
    >
        <!-- Back Link -->
        <div>
            <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Peminjaman
            </a>
        </div>

        <form method="POST" action="{{ route('admin.loans.store') }}">
            @csrf

            <x-card class="p-6 border border-gray-100 dark:border-gray-700/60 shadow-sm space-y-6">
                <div class="border-b border-gray-100 dark:border-gray-700/60 pb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Formulir Peminjaman
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Pilih anggota dan 1 sampai 3 buku yang akan dipinjam (durasi standar 7 hari).
                    </p>
                </div>

                <!-- 1. Pilih Anggota -->
                <div>
                    <x-input-label for="user_id" value="Pilih Anggota Peminjam *" />
                    <select
                        id="user_id"
                        name="user_id"
                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        required
                    >
                        <option value="">-- Pilih Anggota Perpustakaan --</option>
                        @foreach ($members as $m)
                            <option value="{{ $m->id }}" {{ (old('user_id') == $m->id || request('user_id') == $m->id) ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->email }}) - Sedang Pinjam: {{ $m->activeBorrowedBooksCount() }} Buku
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                </div>

                <!-- 2. Tanggal Transaksi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="tanggal_pinjam" value="Tanggal Pinjam *" />
                        <x-text-input
                            id="tanggal_pinjam"
                            name="tanggal_pinjam"
                            type="date"
                            value="{{ old('tanggal_pinjam', $defaultPinjam) }}"
                            class="mt-1 block w-full"
                            required
                        />
                        <x-input-error :messages="$errors->get('tanggal_pinjam')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_jatuh_tempo" value="Tanggal Jatuh Tempo (Default +7 Hari) *" />
                        <x-text-input
                            id="tanggal_jatuh_tempo"
                            name="tanggal_jatuh_tempo"
                            type="date"
                            value="{{ old('tanggal_jatuh_tempo', $defaultJatuhTempo) }}"
                            class="mt-1 block w-full"
                            required
                        />
                        <x-input-error :messages="$errors->get('tanggal_jatuh_tempo')" class="mt-1" />
                    </div>
                </div>

                <!-- 3. Pilihan Buku -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <x-input-label value="Pilih Buku (Maksimal 3 Buku) *" />
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                            Terpilih: <span x-text="selectedBooks.length"></span> / 3
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-1 border border-gray-200 dark:border-gray-700 rounded-xl">
                        @foreach ($books as $b)
                            <label
                                class="relative flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all duration-150 select-none"
                                :class="selectedBooks.includes({{ $b->id }}) ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 dark:border-indigo-500' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-700/50'"
                            >
                                <input
                                    type="checkbox"
                                    name="book_ids[]"
                                    value="{{ $b->id }}"
                                    @click="toggleBook({{ $b->id }})"
                                    :checked="selectedBooks.includes({{ $b->id }})"
                                    class="rounded text-indigo-600 focus:ring-indigo-500"
                                >
                                <div class="w-10 h-14 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                    @if ($b->cover)
                                        <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $b->judul }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ $b->penulis }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $b->category->nama ?? '-' }}</span>
                                        <span class="text-[10px] font-semibold text-emerald-600">Stok: {{ $b->stok }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('book_ids')" class="mt-1" />
                </div>

                <!-- Submit Button Bar -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700/60">
                    <a href="{{ route('admin.loans.index') }}">
                        <x-button type="button" variant="secondary">
                            Batal
                        </x-button>
                    </a>
                    <x-button type="submit" variant="primary" :disabled="selectedBooks.length === 0">
                        Simpan Transaksi Peminjaman
                    </x-button>
                </div>
            </x-card>
        </form>
    </div>
</x-app-layout>
