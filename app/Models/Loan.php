<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'denda',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
            'denda' => 'integer',
        ];
    }

    /**
     * Relasi ke anggota peminjam.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke buku melalui tabel loan_details.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'loan_details')->withTimestamps();
    }

    /**
     * Relasi ke item detail peminjaman.
     */
    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    /**
     * Scope pinjaman aktif (masih dipinjam atau terlambat).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['dipinjam', 'terlambat']);
    }

    /**
     * Scope pinjaman terlambat.
     */
    public function scopeLate(Builder $query): Builder
    {
        return $query->where('status', 'terlambat');
    }

    /**
     * Hitung estimasi atau total denda keterlambatan (Rp1.000/hari).
     */
    public function hitungDenda(?Carbon $tanggalKembali = null): int
    {
        $targetDate = $tanggalKembali ?? ($this->tanggal_kembali ?? Carbon::today());
        $tempo = Carbon::parse($this->tanggal_jatuh_tempo);

        if ($targetDate->greaterThan($tempo)) {
            $selisihHari = $tempo->diffInDays($targetDate);
            return (int) ($selisihHari * 1000);
        }

        return 0;
    }
}
