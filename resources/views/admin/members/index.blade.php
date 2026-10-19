<x-app-layout>
    <x-slot name="header">
        Kelola Anggota Perpustakaan
    </x-slot>

    <div
        x-data="{
            createModalOpen: false,
            editModalOpen: false,
            deleteModalOpen: false,
            editId: null,
            editName: '',
            editEmail: '',
            deleteId: null,
            deleteName: '',
            deleteAction: '',
            openEdit(id, name, email) {
                this.editId = id;
                this.editName = name;
                this.editEmail = email;
                this.editModalOpen = true;
            },
            openDelete(id, name, actionUrl) {
                this.deleteId = id;
                this.deleteName = name;
                this.deleteAction = actionUrl;
                this.deleteModalOpen = true;
            }
        }"
        class="space-y-6"
    >
        <!-- Top Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.members.index') }}" class="flex items-center gap-2 max-w-md w-full">
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau email anggota..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white placeholder-gray-400 shadow-sm"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <x-button type="submit" variant="secondary" size="md">
                    Cari
                </x-button>
                @if ($search)
                    <a href="{{ route('admin.members.index') }}" class="p-2.5 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </form>

            <!-- Add Member Button -->
            <x-button @click="createModalOpen = true" variant="primary" size="md" class="flex items-center gap-2 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Daftarkan Anggota</span>
            </x-button>
        </div>

        <!-- Members Table Card -->
        <x-card class="overflow-hidden p-0 border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/75 dark:bg-gray-700/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4 text-center">Pinjaman Aktif</th>
                            <th class="px-6 py-4 text-center">Total Riwayat</th>
                            <th class="px-6 py-4">Bergabung</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse ($members as $member)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 font-bold flex items-center justify-center text-sm border border-indigo-200 dark:border-indigo-800">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ $member->name }}</div>
                                        <div class="text-xs text-gray-400">ID: #USR-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $member->email }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($member->active_loans_count > 0)
                                        <x-badge variant="dipinjam">
                                            {{ $member->active_loans_count }} Transaksi
                                        </x-badge>
                                    @else
                                        <span class="text-xs text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $member->total_loans_count }} kali
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $member->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a
                                        href="{{ route('admin.members.show', $member) }}"
                                        class="inline-flex p-1.5 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors"
                                        title="Detail & Riwayat"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button
                                        type="button"
                                        @click="openEdit({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->email) }}')"
                                        class="p-1.5 rounded-lg text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors"
                                        title="Edit Data Anggota"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        @click="openDelete({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ route('admin.members.destroy', $member) }}')"
                                        class="p-1.5 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors"
                                        title="Hapus Anggota"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-base font-medium text-gray-700 dark:text-gray-300">Belum ada data anggota</p>
                                        <p class="text-xs text-gray-400 mt-1">Daftarkan anggota baru perpustakaan untuk mulai mencatat transaksi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($members->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $members->links() }}
                </div>
            @endif
        </x-card>

        <!-- Modal Tambah Anggota -->
        <x-modal name="create-member-modal" show="createModalOpen">
            <form method="POST" action="{{ route('admin.members.store') }}" class="p-6">
                @csrf
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Daftarkan Anggota Baru
                </h3>

                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Contoh: Budi Santoso" required />
                    </div>

                    <div>
                        <x-input-label for="email" value="Alamat Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="budi@example.com" required />
                    </div>

                    <div>
                        <x-input-label for="password" value="Kata Sandi (Password)" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Minimal 8 karakter" required />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" @click="createModalOpen = false" variant="secondary">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Simpan Anggota
                    </x-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Edit Anggota -->
        <x-modal name="edit-member-modal" show="editModalOpen">
            <form method="POST" :action="'{{ url('admin/members') }}/' + editId" class="p-6">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    Edit Data Anggota
                </h3>

                <div class="space-y-4">
                    <div>
                        <x-input-label for="edit_name" value="Nama Lengkap" />
                        <x-text-input id="edit_name" name="name" type="text" x-model="editName" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="edit_email" value="Alamat Email" />
                        <x-text-input id="edit_email" name="email" type="email" x-model="editEmail" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="edit_password" value="Password Baru (Kosongkan jika tidak diganti)" />
                        <x-text-input id="edit_password" name="password" type="password" class="mt-1 block w-full" placeholder="Minimal 8 karakter jika diubah" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" @click="editModalOpen = false" variant="secondary">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Perbarui Data
                    </x-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Konfirmasi Hapus Anggota -->
        <x-modal name="delete-member-modal" show="deleteModalOpen">
            <form method="POST" :action="deleteAction" class="p-6">
                @csrf
                @method('DELETE')
                <div class="flex items-center gap-3 text-rose-600 dark:text-rose-400 mb-3">
                    <div class="p-2 bg-rose-100 dark:bg-rose-950/60 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Hapus Akun Anggota
                    </h3>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus anggota <span class="font-semibold text-gray-900 dark:text-white" x-text="deleteName"></span>? Tindakan ini tidak dapat dibatalkan. Anggota dengan peminjaman aktif tidak dapat dihapus.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" @click="deleteModalOpen = false" variant="secondary">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="danger">
                        Hapus Permanen
                    </x-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
