<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'stok',
        'cover',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'stok' => 'integer',
        ];
    }

    /**
     * Relasi ke kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke peminjaman melalui loan_details.
     */
    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loan_details')->withTimestamps();
    }

    /**
     * Relasi detail peminjaman.
     */
    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    /**
     * Scope buku yang tersedia (stok > 0).
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope pencarian judul, penulis, atau penerbit.
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('penulis', 'like', "%{$keyword}%")
              ->orWhere('penerbit', 'like', "%{$keyword}%");
        });
    }
}
