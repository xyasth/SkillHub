@extends('layouts.app')
@section('title', 'Dashboard Peserta')

@section('content')
<div class="mb-8 bg-blue-50 p-6 rounded-lg flex justify-between items-center border border-blue-100">
    <div>
        <h1 class="text-2xl font-bold text-blue-800">Selamat Belajar, {{ Auth::user()->username }}!</h1>
        <p class="text-blue-600">Pantau status pendaftaran dan kelas aktif Anda di sini.</p>
    </div>
    <a href="{{ route('peserta.kelas.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
        + Cari Kelas Baru
    </a>
</div>

{{-- BAGIAN 1: STATUS PENDAFTARAN (PENDING) --}}
@if($kelasPending->count() > 0)
<div class="mb-10">
    <h2 class="text-xl font-bold mb-4 text-yellow-700 flex items-center">
        <span>⏳ Menunggu Persetujuan Instruktur</span>
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($kelasPending as $p)
        <div class="bg-white border border-yellow-200 rounded-lg shadow-sm hover:shadow-md transition overflow-hidden relative">
            <div class="h-1 w-full bg-yellow-400 absolute top-0"></div>
            <div class="p-5">
                <h3 class="font-bold text-lg mb-1 text-gray-800">{{ $p->kelas->nama_kelas }}</h3>
                <p class="text-sm text-gray-500 mb-3">Instruktur: {{ $p->kelas->instruktur->username }}</p>
                
                <div class="text-xs text-gray-400 mb-4 flex justify-between">
                    <span>Daftar: {{ $p->tanggal_daftar->format('d M Y') }}</span>
                </div>

                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded font-bold">
                        Pending
                    </span>
                    
                    {{-- TOMBOL BATAL PENDAFTARAN --}}
                    <form action="{{ route('peserta.kelas.batal', $p->kelas_id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pendaftaran kelas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold hover:underline transition">
                            Batal Daftar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BAGIAN 2: KELAS AKTIF (APPROVED) --}}
<div>
    <h2 class="text-xl font-bold mb-4 text-green-800 flex items-center">
        <span>✅ Kelas Saya (Aktif)</span>
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($kelasDiikuti as $p)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $p->kelas->nama_kelas }}</h3>
                <p class="text-sm text-gray-500 mb-4">Instruktur: {{ $p->kelas->instruktur->username }}</p>
                
                <div class="mb-4">
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold">
                        Status: Aktif
                    </span>
                </div>
                
                <a href="{{ route('peserta.kelas.show', $p->kelas_id) }}" class="block w-full text-center bg-gray-800 text-white py-2 rounded hover:bg-gray-900 transition">
                    Masuk Kelas
                </a>
            </div>
        </div>
        @empty
        @if($kelasPending->count() == 0)
            <div class="col-span-3 text-center py-12 bg-white rounded-lg shadow-sm border-dashed border-2 border-gray-300">
                <p class="text-gray-500 mb-4 text-lg">Anda belum mengikuti kelas apapun.</p>
                <a href="{{ route('peserta.kelas.index') }}" class="text-blue-600 font-bold hover:underline text-lg">
                    Mulai Cari Kelas Sekarang &rarr;
                </a>
            </div>
        @else
            <div class="col-span-3 py-4 text-gray-500 italic">
                Belum ada kelas aktif. (Cek bagian "Menunggu Persetujuan" di atas)
            </div>
        @endif
        @endforelse
    </div>
</div>
@endsection