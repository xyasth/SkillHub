<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstrukturController extends Controller
{
    // Dashboard Instruktur
    public function index()
    {
        // Ambil kelas yang diajar oleh instruktur yang login
        $kelasSaya = Kelas::where('instructor_id', Auth::id())
            ->withCount('pendaftarans') // Hitung jumlah peserta
            ->get();

        return view('instruktur.dashboard', compact('kelasSaya'));
    }

    // Form Tambah Kelas
    public function create()
    {
        return view('instruktur.kelas.create');
    }

    // Simpan Kelas Baru
    // Di method store()
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'deskripsi' => 'required',
            'status' => 'required|in:aktif,non_aktif,discontinued', // Validasi baru
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ]);

        Kelas::create([
            'instructor_id' => Auth::id(),
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,          // Simpan status
            'tgl_mulai' => $request->tgl_mulai,    // Simpan tanggal
            'tgl_selesai' => $request->tgl_selesai,
        ]);

        return redirect()->route('instruktur.dashboard')->with('success', 'Kelas berhasil dibuat');
    }

    // Lakukan hal yang sama untuk method update()

    // Edit Kelas
    public function edit($id)
    {
        $kelas = Kelas::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        return view('instruktur.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('instruktur.dashboard')->with('success', 'Kelas diperbarui');
    }

    // Lihat Peserta di Kelas Tertentu (Flow 3.2)
    // Lihat Peserta di Kelas Tertentu (Flow 3.2)
    public function showPeserta($kelas_id)
    {
        // Pastikan kelas ini milik instruktur yang login
        $kelas = Kelas::where('id', $kelas_id)->where('instructor_id', Auth::id())->firstOrFail();

        // Ambil pendaftaran pending dengan sorting UNIVERSAL (Support SQLite & MySQL)
        $pendaftarans = Pendaftaran::with('user')
            ->where('kelas_id', $kelas_id)
            ->orderByRaw("CASE status
                            WHEN 'pending' THEN 1
                            WHEN 'approved' THEN 2
                            ELSE 3
                        END ASC")
            ->get();

        return view('instruktur.peserta.index', compact('kelas', 'pendaftarans'));
    }

    // Approve / Reject Peserta (Flow 3.3)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // Pastikan pendaftaran ini ada di kelas milik instruktur ini
        $pendaftaran = Pendaftaran::whereHas('kelas', function ($q) {
            $q->where('instructor_id', Auth::id());
        })->findOrFail($id);

        $pendaftaran->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status peserta berhasil diubah menjadi ' . $request->status);
    }
}
