<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('anggota.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('books', \App\Http\Controllers\Admin\BookController::class)->except(['show']);
    Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);
    Route::resource('loans', \App\Http\Controllers\Admin\LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::post('loans/{loan}/return', [\App\Http\Controllers\Admin\LoanController::class, 'returnBook'])->name('loans.return');

    // Laporan & Export PDF
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.pdf');
});

// Anggota Routes
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Anggota\DashboardController::class)->name('dashboard');
    Route::get('/catalog', [\App\Http\Controllers\Anggota\CatalogController::class, 'index'])->name('catalog.index');
    Route::post('/catalog/borrow', [\App\Http\Controllers\Anggota\CatalogController::class, 'borrow'])->name('catalog.borrow');
    Route::get('/loans', [\App\Http\Controllers\Anggota\LoanHistoryController::class, 'index'])->name('loans.index');
    Route::get('/fines', [\App\Http\Controllers\Anggota\LoanHistoryController::class, 'fines'])->name('fines.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
