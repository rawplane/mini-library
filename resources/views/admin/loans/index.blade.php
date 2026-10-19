<x-app-layout>
    <x-slot name="header">
        Peminjaman & Sirkulasi Buku
    </x-slot>

    <div
        x-data="{
            returnModalOpen: false,
            returnLoanId: null,
            returnActionUrl: '',
            borrowerName: '',
            dueDateStr: '',
            returnDate: '{{ now()->toDateString() }}',
            calculatedFine: 0,
            openReturn(loanId, actionUrl, name, dueDate) {
                this.returnLoanId = loanId;
                this.returnActionUrl = actionUrl;
                this.borrowerName = name;
                this.dueDateStr = dueDate;
                this.returnDate = '{{ now()->toDateString() }}';
                this.calculateFine();
                this.returnModalOpen = true;
            },
            calculateFine() {
                if (!this.dueDateStr || !this.returnDate) {
                    this.calculatedFine = 0;
                    return;
                }
                const due = new Date(this.dueDateStr);
                const ret = new Date(this.returnDate);
                due.setHours(0, 0, 0, 0);
                ret.setHours(0, 0, 0, 0);
                const diffTime = ret - due;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays > 0) {
                    this.calculatedFine = diffDays * 1000;
                } else {
                    this.calculatedFine = 0;
                }
            }
        }"
        class="space-y-6"
    >
        <!-- Top Toolbar & Status Filter Pills -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Filter Status Tabs -->
            <div class="flex flex-wrap items-center gap-1.5 p-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm text-xs font-semibold">
                <a
                    href="{{ route('admin.loans.index') }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Semua
                </a>
                <a
                    href="{{ route('admin.loans.index', ['status' => 'aktif']) }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ $status === 'aktif' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Sedang Dipinjam
                </a>
                <a
                    href="{{ route('admin.loans.index', ['status' => 'terlambat']) }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ $status === 'terlambat' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Terlambat
                </a>
                <a
                    href="{{ route('admin.loans.index', ['status' => 'dikembalikan']) }}"
                    class="px-3.5 py-2 rounded-xl transition-colors {{ $status === 'dikembalikan' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                >
                    Dikembalikan
                </a>
            </div>

            <!-- Right Controls: Search & Button -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <form method="GET" action="{{ route('admin.loans.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                    @if ($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <div class="relative w-full sm:w-64">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari peminjam / judul..."
                            class="w-full pl-9 pr-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white shadow-sm"
                        >
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <x-button type="submit" variant="secondary" size="sm">
                        Cari
                    </x-button>
                </form>

                <a href="{{ route('admin.loans.create') }}" class="w-full sm:w-auto">
                    <x-button variant="primary" size="md" class="w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Catat Peminjaman</span>
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
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Buku yang Dipinjam</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Jatuh Tempo</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Denda</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
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
                                    <div class="font-semibold text-gray-900 dark:text-white text-xs">
                                        {{ $loan->user->name ?? 'Anggota Dihapus' }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ $loan->user->email ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @foreach ($loan->books as $b)
                                            <div class="text-xs font-medium text-gray-900 dark:text-white flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                <span class="truncate max-w-[200px]" title="{{ $b->judul }}">{{ $b->judul }}</span>
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
                                    {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-badge :variant="$effectiveStatus">
                                        {{ ucfirst($effectiveStatus) }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold">
                                    @if ($loan->denda > 0)
                                        <span class="text-rose-600">Rp {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                    @elseif ($isLateUnreturned)
                                        @php $liveDenda = $loan->hitungDenda(now()); @endphp
                                        <span class="text-rose-500 text-[11px]" title="Denda berjalan">
                                            (Rp {{ number_format($liveDenda, 0, ',', '.') }}*)
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a
                                        href="{{ route('admin.loans.show', $loan) }}"
                                        class="inline-flex p-1.5 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors"
                                        title="Detail Transaksi"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    @if ($loan->status !== 'dikembalikan')
                                        <button
                                            type="button"
                                            @click="openReturn({{ $loan->id }}, '{{ route('admin.loans.return', $loan) }}', '{{ addslashes($loan->user->name ?? 'Anggota') }}', '{{ $loan->tanggal_jatuh_tempo->toDateString() }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition-colors"
                                            title="Proses Pengembalian"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Kembalikan</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="text-base font-medium text-gray-700 dark:text-gray-300">Tidak ada data peminjaman</p>
                                        <p class="text-xs text-gray-400 mt-1">Gunakan tombol "Catat Peminjaman" untuk membuat transaksi baru.</p>
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

        <!-- Modal Proses Pengembalian Buku -->
        <x-modal name="return-book-modal" show="returnModalOpen">
            <form method="POST" :action="returnActionUrl" class="p-6">
                @csrf
                <div class="flex items-center gap-3 text-emerald-600 dark:text-emerald-400 mb-4">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-950/60 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Proses Pengembalian Buku
                        </h3>
                        <p class="text-xs text-gray-500">Peminjam: <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="borrowerName"></span></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <x-input-label for="return_date" value="Tanggal Pengembalian Riil" />
                        <x-text-input
                            id="return_date"
                            name="tanggal_kembali"
                            type="date"
                            x-model="returnDate"
                            @change="calculateFine()"
                            class="mt-1 block w-full"
                            required
                        />
                        <p class="text-[11px] text-gray-400 mt-1">Tanggal jatuh tempo: <span x-text="dueDateStr" class="font-semibold text-gray-700 dark:text-gray-300"></span></p>
                    </div>

                    <!-- Fine calculation card -->
                    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Tarif Keterlambatan:</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Rp 1.000 / hari</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm font-bold">
                            <span class="text-gray-900 dark:text-white">Estimasi Denda:</span>
                            <span :class="calculatedFine > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'"
                                  x-text="'Rp ' + Number(calculatedFine).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" @click="returnModalOpen = false" variant="secondary">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="success">
                        Konfirmasi Pengembalian
                    </x-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
