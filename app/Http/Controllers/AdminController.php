<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Dashboard Admin
    public function index()
    {
        $totalUser = User::count();
        $totalKelas = Kelas::count();
        $pendaftarans = Pendaftaran::with(['user', 'kelas'])->latest()->get(); // Read-only view

        return view('admin.dashboard', compact('totalUser', 'totalKelas', 'pendaftarans'));
    }

    // --- MANAJEMEN USER ---

    public function userIndex()
    {
        $users = User::where('role', '!=', 'admin')->get(); // List semua kecuali admin sendiri
        return view('admin.users.index', compact('users'));
    }

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

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        // Cek Ketentuan 4.3: Tidak boleh hapus instruktur jika punya kelas aktif
        if ($user->role === 'instruktur' && $user->kelasAjar()->count() > 0) {
            return back()->with('error', 'Gagal! Instruktur ini masih memiliki kelas aktif.');
        }

        // Cek Ketentuan 4.3: Tidak boleh hapus peserta jika masih terdaftar kelas
        if ($user->role === 'peserta' && $user->pendaftarans()->count() > 0) {
            return back()->with('error', 'Gagal! Peserta ini masih terdaftar di kelas.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    // --- MANAJEMEN KELAS (ADMIN VERSION) ---

    public function kelasIndex()
    {
        $kelas = Kelas::with('instruktur')->get();
        $instrukturs = User::where('role', 'instruktur')->get();
        return view('admin.kelas.index', compact('kelas', 'instrukturs'));
    }

    public function kelasStore(Request $request)
    {
        // Admin bisa bikin kelas dan menunjuk instruktur
        $request->validate([
            'nama_kelas' => 'required',
            'instructor_id' => 'required|exists:users,id',
            'deskripsi' => 'required'
        ]);

        Kelas::create($request->all());

        return back()->with('success', 'Kelas berhasil dibuat oleh Admin');
    }

    public function kelasDestroy($id)
    {
        Kelas::destroy($id);
        return back()->with('success', 'Kelas berhasil dihapus');
    }

    // Menampilkan Form Edit User
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Proses Update Data User
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $id, // Ignore unique current user
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:instruktur,peserta'
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Password diupdate hanya jika diisi (tidak wajib saat edit)
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function userShow($id)
    {
        // Ambil user beserta data pendaftaran dan kelas yang diambilnya
        $user = User::with(['pendaftarans.kelas.instruktur'])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }
}
