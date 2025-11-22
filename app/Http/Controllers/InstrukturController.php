<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class InstrukturController
 * * Mengelola kelas milik instruktur dan persetujuan peserta.
 * * @package App\Http\Controllers
 */
class InstrukturController extends Controller
{
    /**
     * Dashboard Instruktur: Menampilkan kelas yang diajar.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        $kelasSaya = Kelas::where('instructor_id', Auth::id())
                          ->withCount('pendaftarans')
                          ->get();

        return view('instruktur.dashboard', compact('kelasSaya'));
    }

    /**
     * Form tambah kelas baru.
     */
    public function create()
    {
        return view('instruktur.kelas.create');
    }

    /**
     * Menyimpan kelas baru dengan status dan tanggal.
     * * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'deskripsi' => 'required',
            'status' => 'required|in:aktif,non_aktif,discontinued',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ]);

        Kelas::create([
            'instructor_id' => Auth::id(),
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
        ]);

        return redirect()->route('instruktur.dashboard')->with('success', 'Kelas berhasil dibuat');
    }

    /**
     * Menampilkan form edit kelas (Hanya kelas milik sendiri).
     */
    public function edit($id)
    {
        $kelas = Kelas::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        return view('instruktur.kelas.edit', compact('kelas'));
    }

    /**
     * Mengupdate data kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

        $request->validate([
            'nama_kelas' => 'required',
            'deskripsi' => 'required',
            // Validasi status dan tanggal jika perlu diupdate
        ]);

        $kelas->update($request->all());

        return redirect()->route('instruktur.dashboard')->with('success', 'Kelas diperbarui');
    }

    /**
     * Menampilkan daftar peserta pada kelas tertentu.
     * * @param int $kelas_id
     * @return \Illuminate\View\View
     */
    public function showPeserta($kelas_id)
    {
        $kelas = Kelas::where('id', $kelas_id)->where('instructor_id', Auth::id())->firstOrFail();

        // Logic Sorting Universal (MySQL/SQLite)
        $pendaftarans = Pendaftaran::with('user')
                        ->where('kelas_id', $kelas_id)
                        ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'approved' THEN 2 ELSE 3 END ASC")
                        ->get();

        return view('instruktur.peserta.index', compact('kelas', 'pendaftarans'));
    }

    /**
     * Mengubah status pendaftaran peserta (Approve/Reject).
     * * @param Request $request
     * @param int $id ID Pendaftaran
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $pendaftaran = Pendaftaran::whereHas('kelas', function($q){
            $q->where('instructor_id', Auth::id());
        })->findOrFail($id);

        $pendaftaran->update(['status' => $request->status]);

        return back()->with('success', 'Status peserta diperbarui.');
    }
}
