<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PesertaController
 * Mengelola aktivitas peserta: Lihat katalog, Daftar kelas, Batal kelas.
 */
class PesertaController extends Controller
{
    /**
     * Dashboard Peserta: Menampilkan kelas yang sedang diikuti (Approved)
     * dan kelas yang sedang menunggu konfirmasi (Pending).
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil kelas yang SUDAH DISETUJUI (Approved)
        $kelasDiikuti = Pendaftaran::with(['kelas.instruktur'])
                        ->where('user_id', $userId)
                        ->where('status', 'approved')
                        ->get();

        // 2. Ambil kelas yang MASIH PENDING (Menunggu Konfirmasi)
        $kelasPending = Pendaftaran::with(['kelas.instruktur'])
                        ->where('user_id', $userId)
                        ->where('status', 'pending')
                        ->get();

        return view('peserta.dashboard', compact('kelasDiikuti', 'kelasPending'));
    }

    /**
     * Menampilkan katalog semua kelas yang berstatus 'aktif'.
     */
    public function listKelas()
    {
        $semuaKelas = Kelas::with('instruktur')
                        ->where('status', 'aktif')
                        ->latest()
                        ->get();
        return view('peserta.kelas.index', compact('semuaKelas'));
    }

    /**
     * Menampilkan detail satu kelas.
     */
    public function showKelas($id)
    {
        $kelas = Kelas::with(['instruktur'])->findOrFail($id);

        $statusPendaftaran = Pendaftaran::where('user_id', Auth::id())
                            ->where('kelas_id', $id)
                            ->first();

        return view('peserta.kelas.show', compact('kelas', 'statusPendaftaran'));
    }

    /**
     * Proses mendaftar ke kelas baru.
     */
    public function daftar(Request $request, $kelas_id)
    {
        // Cek duplikasi pendaftaran
        $cek = Pendaftaran::where('user_id', Auth::id())
                          ->where('kelas_id', $kelas_id)
                          ->exists();

        if ($cek) {
            return back()->with('error', 'Anda sudah mendaftar di kelas ini.');
        }

        Pendaftaran::create([
            'user_id' => Auth::id(),
            'kelas_id' => $kelas_id,
            'status' => 'pending',
            'tanggal_daftar' => now()
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Pendaftaran berhasil dikirim. Tunggu persetujuan instruktur.');
    }

    /**
     * Membatalkan pendaftaran kelas (Unenroll).
     * Hanya bisa dilakukan jika status masih pending atau peserta ingin keluar.
     */
    public function batalDaftar($id)
    {
        // Cari data pendaftaran milik user ini untuk kelas yang dimaksud
        $pendaftaran = Pendaftaran::where('user_id', Auth::id())
                                  ->where('kelas_id', $id) // $id di sini adalah kelas_id (sesuai route)
                                  ->firstOrFail();

        $pendaftaran->delete();

        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}
