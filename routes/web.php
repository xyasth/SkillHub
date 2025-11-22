<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstrukturController;
use App\Http\Controllers\PesertaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect halaman awal ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// --- AUTHENTICATION ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- GROUP PESERTA ---
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'index'])->name('dashboard');

    // Perhatikan nama route ini: 'peserta.kelas.index'
    Route::get('/kelas', [PesertaController::class, 'listKelas'])->name('kelas.index');
    Route::get('/kelas/{id}', [PesertaController::class, 'showKelas'])->name('kelas.show');
    Route::post('/kelas/{id}/daftar', [PesertaController::class, 'daftar'])->name('kelas.daftar');
});

// --- GROUP INSTRUKTUR ---
Route::middleware(['auth', 'role:instruktur'])->prefix('instruktur')->name('instruktur.')->group(function () {
    Route::get('/dashboard', [InstrukturController::class, 'index'])->name('dashboard');

    // Manual Route untuk CRUD Kelas agar penamaan terkontrol
    Route::get('/kelas', [InstrukturController::class, 'index'])->name('kelas.index'); // index
    Route::get('/kelas/create', [InstrukturController::class, 'create'])->name('kelas.create'); // create
    Route::post('/kelas', [InstrukturController::class, 'store'])->name('kelas.store'); // store
    Route::get('/kelas/{id}/edit', [InstrukturController::class, 'edit'])->name('kelas.edit'); // edit
    Route::put('/kelas/{id}', [InstrukturController::class, 'update'])->name('kelas.update'); // update
    Route::get('/kelas/{id}/peserta', [InstrukturController::class, 'showPeserta'])->name('kelas.peserta'); // show peserta

    // Update status pendaftaran
    Route::patch('/pendaftaran/{id}', [InstrukturController::class, 'updateStatus'])->name('pendaftaran.update');
});

// --- GROUP ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');

    // --- TAMBAHAN BARU ---
    Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('users.show'); // Detail Peserta
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit'); // Form Edit
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update'); // Proses Update
    // ---------------------

    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');
    // Kelas Management
    Route::get('/kelas', [AdminController::class, 'kelasIndex'])->name('kelas.index');
    Route::post('/kelas', [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::delete('/kelas/{id}', [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');
});
