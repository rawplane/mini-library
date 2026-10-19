<x-app-layout>
    <x-slot name="header">Kelola Kategori Buku</x-slot>

    <div x-data="{
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        editCategory: { id: null, nama: '' },
        deleteUrl: '',
        deleteName: '',
        openEdit(id, nama) {
            this.editCategory = { id, nama };
            this.showEditModal = true;
        },
        openDelete(url, name) {
            this.deleteUrl = url;
            this.deleteName = name;
            this.showDeleteModal = true;
        }
    }" class="space-y-6">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kategori Buku</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola klasifikasi dan pengelompokan buku perpustakaan.</p>
            </div>
            <div>
                <x-button @click="showCreateModal = true" class="shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kategori
                </x-button>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <x-card :padding="false" class="p-4">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama kategori..."
                        class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white placeholder-gray-400"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-2">
                    <x-button type="submit" variant="secondary" size="md">
                        Cari
                    </x-button>
                    @if(request('search'))
                        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Categories Table -->
        <x-card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/80 dark:bg-gray-900/40 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th scope="col" class="px-6 py-4">#</th>
                            <th scope="col" class="px-6 py-4">Nama Kategori</th>
                            <th scope="col" class="px-6 py-4">Jumlah Koleksi Buku</th>
                            <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse ($categories as $index => $category)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-750/50 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                    {{ $categories->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $category->nama }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/50 dark:border-indigo-800/40">
                                        {{ $category->books_count }} Judul Buku
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEdit({{ $category->id }}, '{{ addslashes($category->nama) }}')"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 dark:hover:text-indigo-400 transition-colors"
                                            title="Edit Kategori"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            @click="openDelete('{{ route('admin.categories.destroy', $category) }}', '{{ addslashes($category->nama) }}')"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 dark:hover:text-red-400 transition-colors"
                                            title="Hapus Kategori"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-base font-semibold text-gray-700 dark:text-gray-200">Belum ada kategori buku</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs">
                                            @if(request('search'))
                                                Tidak ditemukan kategori dengan kata kunci "{{ request('search') }}".
                                            @else
                                                Mulai tambahkan kategori pertama untuk mengelompokkan buku perpustakaan.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $categories->links() }}
                </div>
            @endif
        </x-card>

        <!-- Modal Tambah Kategori -->
        <x-modal name="create-category" show="showCreateModal" focusable>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="p-6">
                @csrf
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tambah Kategori Baru</h3>
                    <button type="button" @click="showCreateModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <x-input-label for="nama_kategori" value="Nama Kategori" />
                        <x-text-input
                            id="nama_kategori"
                            name="nama"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Contoh: Pemrograman & Komputer"
                            required
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="secondary" @click="showCreateModal = false">
                        Batal
                    </x-button>
                    <x-button type="submit">
                        Simpan Kategori
                    </x-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Edit Kategori -->
        <x-modal name="edit-category" show="showEditModal" focusable>
            <form :action="'{{ url('admin/categories') }}/' + editCategory.id" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Kategori</h3>
                    <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <x-input-label for="edit_nama_kategori" value="Nama Kategori" />
                        <input
                            id="edit_nama_kategori"
                            name="nama"
                            type="text"
                            x-model="editCategory.nama"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-xl shadow-sm text-sm"
                            required
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="secondary" @click="showEditModal = false">
                        Batal
                    </x-button>
                    <x-button type="submit">
                        Perbarui Kategori
                    </x-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Konfirmasi Hapus -->
        <x-modal name="delete-category" show="showDeleteModal" focusable>
            <form :action="deleteUrl" method="POST" class="p-6">
                @csrf
                @method('DELETE')

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Hapus Kategori</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Apakah Anda yakin ingin menghapus kategori <span class="font-semibold text-gray-700 dark:text-gray-200" x-text="deleteName"></span>?
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="secondary" @click="showDeleteModal = false">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="danger">
                        Ya, Hapus Kategori
                    </x-button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>
