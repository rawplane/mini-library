<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'anggota')),
            ],
            'book_ids' => ['required', 'array', 'min:1', 'max:3'],
            'book_ids.*' => ['required', 'distinct', 'exists:books,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_jatuh_tempo' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Peminjam (anggota) wajib dipilih.',
            'user_id.exists' => 'Anggota yang dipilih tidak valid.',
            'book_ids.required' => 'Pilih minimal satu buku untuk dipinjam.',
            'book_ids.array' => 'Format pilihan buku tidak valid.',
            'book_ids.min' => 'Pilih minimal :min buku.',
            'book_ids.max' => 'Maksimal :max buku dalam satu transaksi.',
            'book_ids.*.distinct' => 'Buku yang dipilih tidak boleh duplikat.',
            'book_ids.*.exists' => 'Buku yang dipilih tidak ditemukan.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum tanggal pinjam.',
        ];
    }
}
