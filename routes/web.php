<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstrukturController;
use App\Http\Controllers\PesertaController;

/*
|--------------------------------------------------------------------------
| Web Routes (Dokumentasi Routing Aplikasi SkillHub)
|--------------------------------------------------------------------------
|
| File ini mendefinisikan seluruh URL yang ada dalam aplikasi.
| Menggunakan Middleware untuk membatasi akses berdasarkan Role:
| 1. Admin      -> Akses penuh manajemen user & kelas.
| 2. Instruktur -> Akses manajemen kelas sendiri & approval peserta.
| 3. Peserta    -> Akses katalog kelas & pendaftaran.
|
*/

// ====================================================
// 1. HALAMAN PUBLIK & OTENTIKASI
// ====================================================

/**
 * Halaman Utama (Root).
 * Mengarahkan pengguna langsung ke halaman login.
 */
Route::get('/', function () {
    return redirect()->route('login');
});

/**
 * Route Otentikasi (Login & Logout).
 * Menangani proses masuk dan keluar sistem.
 */
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ====================================================
// 2. GROUP ROUTE: PESERTA
// Middleware: Wajib Login & Role harus 'peserta'
// ====================================================
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {

    // Dashboard utama peserta (Menampilkan kelas yang sedang diikuti)
    Route::get('/dashboard', [PesertaController::class, 'index'])->name('dashboard');

    // Katalog Kelas
    Route::get('/kelas', [PesertaController::class, 'listKelas'])->name('kelas.index');
    Route::get('/kelas/{id}', [PesertaController::class, 'showKelas'])->name('kelas.show');

    // Aksi Pendaftaran
    Route::post('/kelas/{id}/daftar', [PesertaController::class, 'daftar'])->name('kelas.daftar');

    // Aksi Pembatalan Pendaftaran (Fitur Hapus)
    Route::delete('/kelas/{id}/batal', [PesertaController::class, 'batalDaftar'])->name('kelas.batal');
});


// ====================================================
// 3. GROUP ROUTE: INSTRUKTUR
// Middleware: Wajib Login & Role harus 'instruktur'
// ====================================================
Route::middleware(['auth', 'role:instruktur'])->prefix('instruktur')->name('instruktur.')->group(function () {

    // Dashboard Instruktur (Statistik & List Kelas Sendiri)
    Route::get('/dashboard', [InstrukturController::class, 'index'])->name('dashboard');

    // Manajemen Kelas (CRUD)
    // Menggunakan route manual agar penamaan lebih terkontrol dibanding Route::resource
    Route::get('/kelas', [InstrukturController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [InstrukturController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [InstrukturController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{id}/edit', [InstrukturController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{id}', [InstrukturController::class, 'update'])->name('kelas.update');

    // Manajemen Peserta dalam Kelas
    Route::get('/kelas/{id}/peserta', [InstrukturController::class, 'showPeserta'])->name('kelas.peserta');

    // Proses Approval/Rejection Pendaftaran
    Route::patch('/pendaftaran/{id}', [InstrukturController::class, 'updateStatus'])->name('pendaftaran.update');
});


// ====================================================
// 4. GROUP ROUTE: ADMIN (SUPERUSER)
// Middleware: Wajib Login & Role harus 'admin'
// ====================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin (Statistik Global)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    /**
     * Manajemen User (CRUD Lengkap).
     * Admin dapat menambah, mengedit, melihat detail, dan menghapus user.
     */
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('users.show'); // Detail User
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit'); // Form Edit
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update'); // Proses Update
    Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

    /**
     * Manajemen Kelas (Admin Level).
     * Admin dapat membuat kelas dan menghapusnya (override).
     */
    Route::get('/kelas', [AdminController::class, 'kelasIndex'])->name('kelas.index');
    Route::post('/kelas', [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::delete('/kelas/{id}', [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');
});
