<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Tentukan otorisasi request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi input buku.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentYear = (int) date('Y') + 1;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'integer', 'min:1900', "max:{$currentYear}"],
            'stok' => ['required', 'integer', 'min:0'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Kustomisasi pesan error.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun.required' => 'Tahun terbit wajib diisi.',
            'tahun.min' => 'Tahun terbit minimal tahun 1900.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'stok.min' => 'Jumlah stok tidak boleh negatif.',
            'cover.image' => 'File cover harus berupa gambar.',
            'cover.mimes' => 'Format cover harus jpeg, png, jpg, atau webp.',
            'cover.max' => 'Ukuran cover maksimal 2MB.',
        ];
    }
}
