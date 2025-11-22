<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    // Dashboard Peserta: Menampilkan kelas yang diikuti (Flow 2.4)
    public function index()
    {
        // Ambil kelas dimana user ini terdaftar dan statusnya approved
        $kelasDiikuti = Pendaftaran::with(['kelas.instruktur'])
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->get();

        return view('peserta.dashboard', compact('kelasDiikuti'));
    }

    // List Semua Kelas (Flow 2.1)
    public function listKelas()
    {
        // Peserta hanya melihat kelas yang AKTIF
        $semuaKelas = Kelas::with('instruktur')
            ->where('status', 'aktif') // Filter Status
            ->latest()
            ->get();

        return view('peserta.kelas.index', compact('semuaKelas'));
    }

    // Detail Kelas (Flow 2.2)
    public function showKelas($id)
    {
        $kelas = Kelas::with(['instruktur', 'pendaftarans'])->findOrFail($id);

        // Cek status pendaftaran user saat ini (untuk tombol: Daftar / Menunggu / Terdaftar)
        $statusPendaftaran = Pendaftaran::where('user_id', Auth::id())
            ->where('kelas_id', $id)
            ->first();

        return view('peserta.kelas.show', compact('kelas', 'statusPendaftaran'));
    }
    public function batalDaftar($id)
    {
        // Cari pendaftaran milik user yang sedang login
        $pendaftaran = Pendaftaran::where('user_id', Auth::id())
            ->where('kelas_id', $id)
            ->firstOrFail();

        $pendaftaran->delete(); // Hapus data dari database

        return back()->with('success', 'Anda berhasil membatalkan pendaftaran kelas ini.');
    }

    // Proses Daftar Kelas (Flow 2.3)
    public function daftar(Request $request, $kelas_id)
    {
        // Cek apakah sudah pernah daftar
        $cek = Pendaftaran::where('user_id', Auth::id())
            ->where('kelas_id', $kelas_id)
            ->exists();

        if ($cek) {
            return back()->with('error', 'Anda sudah mendaftar di kelas ini.');
        }

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'kelas_id' => $kelas_id,
            'status' => 'pending', // Default status sesuai spesifikasi
            'tanggal_daftar' => now()
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Pendaftaran berhasil dikirim. Menunggu persetujuan Instruktur.');
    }
}
