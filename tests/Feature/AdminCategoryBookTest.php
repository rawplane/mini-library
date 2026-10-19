<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryBookTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $anggota;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->anggota = User::factory()->anggota()->create();
    }

    public function test_guest_and_anggota_cannot_access_admin_categories_and_books(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
        $this->get(route('admin.books.index'))->assertRedirect(route('login'));

        $this->actingAs($this->anggota)->get(route('admin.categories.index'))->assertStatus(403);
        $this->actingAs($this->anggota)->get(route('admin.books.index'))->assertStatus(403);
    }

    public function test_admin_can_view_category_list_and_create_new_category(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);

        $postResponse = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'nama' => 'Teknologi Informasi',
        ]);

        $postResponse->assertRedirect(route('admin.categories.index'));
        $postResponse->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['nama' => 'Teknologi Informasi']);
    }

    public function test_admin_cannot_create_duplicate_category(): void
    {
        Category::create(['nama' => 'Sains']);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'nama' => 'Sains',
        ]);

        $response->assertSessionHasErrors(['nama']);
    }

    public function test_admin_can_update_category(): void
    {
        $cat = Category::create(['nama' => 'Novel Lama']);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $cat), [
            'nama' => 'Novel Baru',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $cat->id, 'nama' => 'Novel Baru']);
    }

    public function test_admin_cannot_delete_category_that_has_books(): void
    {
        $cat = Category::create(['nama' => 'Komik']);
        Book::factory()->create(['category_id' => $cat->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $cat));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $cat->id]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $cat = Category::create(['nama' => 'Kategori Kosong']);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $cat));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_admin_can_create_book_with_cover_upload(): void
    {
        Storage::fake('public');
        $cat = Category::create(['nama' => 'Pemrograman']);

        $coverFile = UploadedFile::fake()->image('cover-test.jpg', 600, 800);

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), [
            'category_id' => $cat->id,
            'judul' => 'Mastering Laravel 11',
            'penulis' => 'Taylor Otwell',
            'penerbit' => 'Laravel LLC',
            'tahun' => 2024,
            'stok' => 10,
            'cover' => $coverFile,
        ]);

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success');

        $book = Book::where('judul', 'Mastering Laravel 11')->first();
        $this->assertNotNull($book);
        $this->assertNotNull($book->cover);
        Storage::disk('public')->assertExists($book->cover);
    }

    public function test_admin_can_update_book_and_replace_cover(): void
    {
        Storage::fake('public');
        $cat = Category::create(['nama' => 'Database']);

        $initialCover = UploadedFile::fake()->image('old-cover.jpg')->store('covers', 'public');
        $book = Book::create([
            'category_id' => $cat->id,
            'judul' => 'High Performance MySQL',
            'penulis' => 'Baron Schwartz',
            'penerbit' => "O'Reilly",
            'tahun' => 2020,
            'stok' => 5,
            'cover' => $initialCover,
        ]);

        $newCover = UploadedFile::fake()->image('new-cover.jpg');

        $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
            'category_id' => $cat->id,
            'judul' => 'High Performance MySQL 4th Edition',
            'penulis' => 'Baron Schwartz',
            'penerbit' => "O'Reilly",
            'tahun' => 2021,
            'stok' => 7,
            'cover' => $newCover,
        ]);

        $response->assertRedirect(route('admin.books.index'));
        $book->refresh();

        $this->assertEquals('High Performance MySQL 4th Edition', $book->judul);
        $this->assertNotEquals($initialCover, $book->cover);
        Storage::disk('public')->assertMissing($initialCover);
        Storage::disk('public')->assertExists($book->cover);
    }

    public function test_admin_cannot_delete_book_with_active_loans(): void
    {
        $cat = Category::create(['nama' => 'Sejarah']);
        $book = Book::factory()->create(['category_id' => $cat->id]);

        $loan = Loan::factory()->create([
            'user_id' => $this->anggota->id,
            'status' => 'dipinjam',
        ]);
        LoanDetail::create([
            'loan_id' => $loan->id,
            'book_id' => $book->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $book));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_admin_can_delete_book_without_active_loans(): void
    {
        Storage::fake('public');
        $cat = Category::create(['nama' => 'Biografi']);
        $coverPath = UploadedFile::fake()->image('bio.jpg')->store('covers', 'public');

        $book = Book::factory()->create([
            'category_id' => $cat->id,
            'cover' => $coverPath,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $book));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        Storage::disk('public')->assertMissing($coverPath);
    }
}
