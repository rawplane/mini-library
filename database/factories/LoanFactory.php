<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pinjam = fake()->dateTimeBetween('-2 months', 'now');
        $tempo = (clone $pinjam)->modify('+7 days');

        return [
            'user_id' => User::factory()->anggota(),
            'tanggal_pinjam' => $pinjam,
            'tanggal_jatuh_tempo' => $tempo,
            'tanggal_kembali' => null,
            'status' => 'dipinjam',
            'denda' => 0,
        ];
    }

    /**
     * State untuk pinjaman yang sudah dikembalikan tepat waktu.
     */
    public function dikembalikan(): static
    {
        return $this->state(function (array $attributes) {
            $pinjam = Carbon::parse($attributes['tanggal_pinjam']);
            $kembali = (clone $pinjam)->addDays(fake()->numberBetween(1, 6));

            return [
                'tanggal_kembali' => $kembali,
                'status' => 'dikembalikan',
                'denda' => 0,
            ];
        });
    }

    /**
     * State untuk pinjaman yang terlambat dan sudah ada denda.
     */
    public function terlambat(): static
    {
        return $this->state(function (array $attributes) {
            $tempo = Carbon::parse($attributes['tanggal_jatuh_tempo']);
            $hariTelat = fake()->numberBetween(2, 10);
            $kembali = (clone $tempo)->addDays($hariTelat);

            return [
                'tanggal_kembali' => $kembali,
                'status' => 'terlambat',
                'denda' => $hariTelat * 1000,
            ];
        });
    }
}
