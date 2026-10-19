<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $anggota;
    private Category $category;
    private Book $book1;
    private Book $book2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->anggota = User::factory()->anggota()->create();
        $this->category = Category::factory()->create(['nama' => 'Teknologi']);

        $this->book1 = Book::factory()->create([
            'category_id' => $this->category->id,
            'judul' => 'Pemrograman Laravel Modern',
            'stok' => 5,
        ]);

        $this->book2 = Book::factory()->create([
            'category_id' => $this->category->id,
            'judul' => 'Desain Antarmuka Tailwind CSS',
            'stok' => 1,
        ]);
    }

    public function test_admin_can_view_members_and_create_member(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));
        $response->assertOk();
        $response->assertSee($this->anggota->name);

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.members.store'), [
            'name' => 'Anggota Baru Test',
            'email' => 'anggota_baru@test.com',
            'password' => 'password123',
        ]);

        $storeResponse->assertRedirect(route('admin.members.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'anggota_baru@test.com',
            'role' => 'anggota',
        ]);
    }

    public function test_admin_cannot_delete_member_with_active_loans(): void
    {
        // Buat pinjaman aktif untuk anggota
        $loan = Loan::create([
            'user_id' => $this->anggota->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach($this->book1->id);

        $response = $this->actingAs($this->admin)->delete(route('admin.members.destroy', $this->anggota));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->anggota->id]);
    }

    public function test_admin_can_record_loan_and_stock_decreases(): void
    {
        $initialStock = $this->book1->stok;

        $response = $this->actingAs($this->admin)->post(route('admin.loans.store'), [
            'user_id' => $this->anggota->id,
            'book_ids' => [$this->book1->id],
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect(route('admin.loans.index'));
        $response->assertSessionHas('success');

        // Pastikan stok berkurang 1
        $this->assertDatabaseHas('books', [
            'id' => $this->book1->id,
            'stok' => $initialStock - 1,
        ]);

        // Pastikan loan tersimpan
        $this->assertDatabaseHas('loans', [
            'user_id' => $this->anggota->id,
            'status' => 'dipinjam',
        ]);
    }

    public function test_cannot_borrow_book_with_zero_stock(): void
    {
        $outOfStockBook = Book::factory()->create([
            'category_id' => $this->category->id,
            'stok' => 0,
        ]);

        $response = $this->actingAs($this->anggota)->post(route('anggota.catalog.borrow'), [
            'book_ids' => [$outOfStockBook->id],
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, Loan::where('user_id', $this->anggota->id)->count());
    }

    public function test_member_cannot_borrow_more_than_three_active_books(): void
    {
        // Anggota sudah meminjam 2 buku
        $loan = Loan::create([
            'user_id' => $this->anggota->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach([$this->book1->id, $this->book2->id]);

        $book3 = Book::factory()->create(['category_id' => $this->category->id, 'stok' => 5]);
        $book4 = Book::factory()->create(['category_id' => $this->category->id, 'stok' => 5]);

        // Coba pinjam 2 buku lagi (total jadi 4 buku, harus ditolak)
        $response = $this->actingAs($this->anggota)->post(route('anggota.catalog.borrow'), [
            'book_ids' => [$book3->id, $book4->id],
        ]);

        $response->assertSessionHas('error');
    }

    public function test_admin_return_process_calculates_fine_and_restores_stock(): void
    {
        $initialStock = $this->book1->stok;

        // Pinjaman yang jatuh tempo 3 hari yang lalu
        $loan = Loan::create([
            'user_id' => $this->anggota->id,
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tanggal_jatuh_tempo' => now()->subDays(3)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach($this->book1->id);
        $this->book1->decrement('stok');

        $this->assertEquals($initialStock - 1, $this->book1->fresh()->stok);

        // Kembalikan hari ini (terlambat 3 hari => denda Rp3.000)
        $response = $this->actingAs($this->admin)->post(route('admin.loans.return', $loan), [
            'tanggal_kembali' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('admin.loans.index'));
        $loan->refresh();

        $this->assertEquals('terlambat', $loan->status);
        $this->assertEquals(3000, $loan->denda);
        $this->assertEquals($initialStock, $this->book1->fresh()->stok);
    }

    public function test_anggota_can_view_catalog_and_fines_page(): void
    {
        $catalogResponse = $this->actingAs($this->anggota)->get(route('anggota.catalog.index'));
        $catalogResponse->assertOk();
        $catalogResponse->assertSee($this->book1->judul);

        $finesResponse = $this->actingAs($this->anggota)->get(route('anggota.fines.index'));
        $finesResponse->assertOk();
        $finesResponse->assertSee('Denda Berjalan');
    }
}
