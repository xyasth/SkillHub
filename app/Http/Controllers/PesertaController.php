<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PesertaController
 * * Mengelola aktivitas peserta: Lihat katalog, Daftar kelas, Batal kelas.
 * * @package App\Http\Controllers
 */
class PesertaController extends Controller
{
    /**
     * Dashboard Peserta: Menampilkan kelas yang sedang diikuti.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        $kelasDiikuti = Pendaftaran::with(['kelas.instruktur'])
                        ->where('user_id', Auth::id())
                        ->where('status', 'approved')
                        ->get();

        return view('peserta.dashboard', compact('kelasDiikuti'));
    }

    /**
     * Menampilkan katalog semua kelas yang berstatus 'aktif'.
     * * @return \Illuminate\View\View
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
     * * @param int $id ID Kelas
     * @return \Illuminate\View\View
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
     * * @param Request $request
     * @param int $kelas_id
     * @return \Illuminate\Http\RedirectResponse
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

        return redirect()->route('peserta.dashboard')->with('success', 'Pendaftaran berhasil dikirim.');
    }

    /**
     * Membatalkan pendaftaran kelas.
     * * @param int $id ID Kelas
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batalDaftar($id)
    {
        $pendaftaran = Pendaftaran::where('user_id', Auth::id())
                                  ->where('kelas_id', $id)
                                  ->firstOrFail();

        $pendaftaran->delete();

        return back()->with('success', 'Pendaftaran dibatalkan.');
    }
}
