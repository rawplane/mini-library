<x-app-layout>
    <x-slot name="header">Edit Buku: {{ $book->judul }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb / Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.books.index') }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke Daftar Buku
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Edit Data Buku</h1>
            </div>
        </div>

        <x-card>
            <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" class="space-y-6"
                  x-data="{
                      coverPreview: '{{ $book->cover ? asset('storage/' . $book->cover) : '' }}',
                      handleCoverChange(event) {
                          const file = event.target.files[0];
                          if (file) {
                              this.coverPreview = URL.createObjectURL(file);
                          }
                      }
                  }">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Judul Buku -->
                    <div class="md:col-span-2">
                        <x-input-label for="judul" value="Judul Buku" />
                        <x-text-input
                            id="judul"
                            name="judul"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('judul', $book->judul) }}"
                            required
                        />
                        <x-input-error class="mt-1" :messages="$errors->get('judul')" />
                    </div>

                    <!-- Kategori -->
                    <div>
                        <x-input-label for="category_id" value="Kategori Buku" />
                        <select
                            id="category_id"
                            name="category_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-xl shadow-sm text-sm"
                            required
                        >
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-1" :messages="$errors->get('category_id')" />
                    </div>

                    <!-- Penulis -->
                    <div>
                        <x-input-label for="penulis" value="Penulis / Pengarang" />
                        <x-text-input
                            id="penulis"
                            name="penulis"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('penulis', $book->penulis) }}"
                            required
                        />
                        <x-input-error class="mt-1" :messages="$errors->get('penulis')" />
                    </div>

                    <!-- Penerbit -->
                    <div>
                        <x-input-label for="penerbit" value="Penerbit" />
                        <x-text-input
                            id="penerbit"
                            name="penerbit"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('penerbit', $book->penerbit) }}"
                            required
                        />
                        <x-input-error class="mt-1" :messages="$errors->get('penerbit')" />
                    </div>

                    <!-- Tahun Terbit -->
                    <div>
                        <x-input-label for="tahun" value="Tahun Terbit" />
                        <x-text-input
                            id="tahun"
                            name="tahun"
                            type="number"
                            min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="mt-1 block w-full"
                            value="{{ old('tahun', $book->tahun) }}"
                            required
                        />
                        <x-input-error class="mt-1" :messages="$errors->get('tahun')" />
                    </div>

                    <!-- Stok Buku -->
                    <div>
                        <x-input-label for="stok" value="Jumlah Stok Fisik" />
                        <x-text-input
                            id="stok"
                            name="stok"
                            type="number"
                            min="0"
                            class="mt-1 block w-full"
                            value="{{ old('stok', $book->stok) }}"
                            required
                        />
                        <x-input-error class="mt-1" :messages="$errors->get('stok')" />
                    </div>

                    <!-- Cover Upload -->
                    <div class="md:col-span-2">
                        <x-input-label for="cover" value="Ganti Sampul Buku (Opsional)" />
                        <div class="mt-2 flex flex-col sm:flex-row items-center gap-6">
                            <!-- Image Preview Area -->
                            <div class="w-28 h-36 rounded-xl bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0 relative">
                                <template x-if="coverPreview">
                                    <img :src="coverPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!coverPreview">
                                    <div class="text-center p-2 text-gray-400">
                                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-[10px] block mt-1">Pratinjau Cover</span>
                                    </div>
                                </template>
                            </div>

                            <!-- File Upload Input -->
                            <div class="flex-1 w-full">
                                <input
                                    id="cover"
                                    name="cover"
                                    type="file"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    @change="handleCoverChange"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950/60 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer"
                                />
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    Unggah file baru jika ingin memperbarui cover buku. Format: JPG, PNG, WEBP (maks. 2MB).
                                </p>
                                <x-input-error class="mt-1" :messages="$errors->get('cover')" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.books.index') }}">
                        <x-button type="button" variant="secondary">
                            Batal
                        </x-button>
                    </a>
                    <x-button type="submit">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
