<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminController
 * * Mengelola fitur Administrator: Manajemen User, Kelas, dan Statistik.
 * * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Admin dengan statistik ringkas.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalUser = User::count();
        $totalKelas = Kelas::count();
        // Read-only view daftar pendaftaran terbaru
        $pendaftarans = Pendaftaran::with(['user', 'kelas'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('totalUser', 'totalKelas', 'pendaftarans'));
    }

    // --- MANAJEMEN USER ---

    /**
     * Menampilkan daftar seluruh user (selain admin).
     * * @return \Illuminate\View\View
     */
    public function userIndex()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menyimpan user baru ke database.
     * * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userStore(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:instruktur,peserta'
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Menampilkan detail user dan kelas yang diikuti.
     * * @param int $id ID User
     * @return \Illuminate\View\View
     */
    public function userShow($id)
    {
        $user = User::with(['pendaftarans.kelas.instruktur'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan form edit user.
     * * @param int $id
     * @return \Illuminate\View\View
     */
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mengupdate data user di database.
     * * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:instruktur,peserta'
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user diperbarui.');
    }

    /**
     * Menghapus user dengan validasi relasi.
     * Tidak boleh menghapus jika user masih aktif di kelas.
     * * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        // Cek constraints: Instruktur punya kelas aktif?
        if ($user->role === 'instruktur' && $user->kelasAjar()->count() > 0) {
            return back()->with('error', 'Gagal! Instruktur ini masih memiliki kelas aktif.');
        }

        // Cek constraints: Peserta punya kelas terdaftar?
        if ($user->role === 'peserta' && $user->pendaftarans()->count() > 0) {
            return back()->with('error', 'Gagal! Peserta ini masih terdaftar di kelas.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    // --- MANAJEMEN KELAS (ADMIN) ---

    /**
     * Menampilkan daftar kelas untuk Admin.
     */
    public function kelasIndex()
    {
        $kelas = Kelas::with('instruktur')->get();
        $instrukturs = User::where('role', 'instruktur')->get();
        return view('admin.kelas.index', compact('kelas', 'instrukturs'));
    }

    /**
     * Admin membuat kelas dan memilihkan instruktur.
     */
    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'instructor_id' => 'required|exists:users,id',
            'deskripsi' => 'required'
        ]);

        Kelas::create($request->all());

        return back()->with('success', 'Kelas berhasil dibuat oleh Admin');
    }

    /**
     * Admin menghapus kelas.
     */
    public function kelasDestroy($id)
    {
        Kelas::destroy($id);
        return back()->with('success', 'Kelas berhasil dihapus');
    }
}
