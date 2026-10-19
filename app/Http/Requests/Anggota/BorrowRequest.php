<?php

namespace App\Http\Requests\Anggota;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAnggota();
    }

    public function rules(): array
    {
        return [
            'book_ids' => ['required', 'array', 'min:1', 'max:3'],
            'book_ids.*' => ['required', 'distinct', 'exists:books,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_ids.required' => 'Pilih minimal satu buku untuk diajukan peminjaman.',
            'book_ids.array' => 'Format pilihan buku tidak valid.',
            'book_ids.min' => 'Pilih minimal :min buku.',
            'book_ids.max' => 'Maksimal :max buku dalam satu transaksi.',
            'book_ids.*.distinct' => 'Buku yang dipilih tidak boleh sama.',
            'book_ids.*.exists' => 'Buku yang dipilih tidak valid atau tidak ditemukan.',
        ];
    }
}
