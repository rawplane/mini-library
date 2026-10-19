<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_books(): void
    {
        $category = Category::factory()->create(['nama' => 'Teknologi']);
        $book1 = Book::factory()->create(['category_id' => $category->id]);
        $book2 = Book::factory()->create(['category_id' => $category->id]);

        $this->assertCount(2, $category->books);
        $this->assertTrue($category->books->contains($book1));
        $this->assertTrue($category->books->contains($book2));
        $this->assertEquals($category->id, $book1->category->id);
    }

    public function test_user_has_many_loans(): void
    {
        $user = User::factory()->anggota()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->loans);
        $this->assertEquals($user->id, $loan->user->id);
    }

    public function test_loan_belongs_to_many_books_through_loan_details(): void
    {
        $user = User::factory()->anggota()->create();
        $category = Category::factory()->create();
        $book1 = Book::factory()->create(['category_id' => $category->id, 'stok' => 5]);
        $book2 = Book::factory()->create(['category_id' => $category->id, 'stok' => 3]);

        $loan = Loan::create([
            'user_id' => $user->id,
            'tanggal_pinjam' => Carbon::today(),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(7),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);

        $loan->books()->attach([$book1->id, $book2->id]);

        $this->assertCount(2, $loan->books);
        $this->assertTrue($loan->books->contains($book1));
        $this->assertTrue($loan->books->contains($book2));
        $this->assertTrue($book1->loans->contains($loan));
    }

    public function test_user_active_loans_and_borrowed_books_count(): void
    {
        $user = User::factory()->anggota()->create();
        $category = Category::factory()->create();
        $books = Book::factory()->count(3)->create(['category_id' => $category->id]);

        $activeLoan = Loan::create([
            'user_id' => $user->id,
            'tanggal_pinjam' => Carbon::today()->subDays(2),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(5),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $activeLoan->books()->attach([$books[0]->id, $books[1]->id]);

        $returnedLoan = Loan::create([
            'user_id' => $user->id,
            'tanggal_pinjam' => Carbon::today()->subDays(20),
            'tanggal_jatuh_tempo' => Carbon::today()->subDays(13),
            'tanggal_kembali' => Carbon::today()->subDays(15),
            'status' => 'dikembalikan',
            'denda' => 0,
        ]);
        $returnedLoan->books()->attach([$books[2]->id]);

        $this->assertCount(1, $user->activeLoans);
        $this->assertEquals(2, $user->activeBorrowedBooksCount());
    }

    public function test_loan_denda_calculation_logic(): void
    {
        $user = User::factory()->anggota()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'tanggal_pinjam' => Carbon::today()->subDays(12),
            'tanggal_jatuh_tempo' => Carbon::today()->subDays(5),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);

        // Terlambat 5 hari: denda 5 * 1000 = 5000
        $this->assertEquals(5000, $loan->hitungDenda(Carbon::today()));
    }
}
