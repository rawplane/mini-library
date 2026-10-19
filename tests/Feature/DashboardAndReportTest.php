<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAndReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_admin_dashboard_with_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->anggota()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stok' => 5]);

        $loan = Loan::create([
            'user_id' => $member->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach($book->id);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Perpustakaan');
        $response->assertSee('Tren Peminjaman Buku');
        $response->assertSee($book->judul);
    }

    public function test_anggota_cannot_access_admin_dashboard(): void
    {
        $member = User::factory()->anggota()->create();

        $response = $this->actingAs($member)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_anggota_can_view_anggota_dashboard_with_quota_and_active_loans(): void
    {
        $member = User::factory()->anggota()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stok' => 3]);

        $loan = Loan::create([
            'user_id' => $member->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach($book->id);

        $response = $this->actingAs($member)->get(route('anggota.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Sisa Kuota Pinjam');
        $response->assertSee($book->judul);
    }

    public function test_admin_can_view_reports_with_filters(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->anggota()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $loan = Loan::create([
            'user_id' => $member->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach($book->id);

        $response = $this->actingAs($admin)->get(route('admin.reports.index', [
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->toDateString(),
            'status' => 'dipinjam',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Peminjaman');
        $response->assertSee($member->name);
        $response->assertSee($book->judul);
    }

    public function test_admin_can_export_report_pdf(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->anggota()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $loan = Loan::create([
            'user_id' => $member->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        $loan->books()->attach($book->id);

        $response = $this->actingAs($admin)->get(route('admin.reports.pdf', [
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_anggota_cannot_access_reports_or_export_pdf(): void
    {
        $member = User::factory()->anggota()->create();

        $this->actingAs($member)->get(route('admin.reports.index'))->assertStatus(403);
        $this->actingAs($member)->get(route('admin.reports.pdf'))->assertStatus(403);
    }
}
