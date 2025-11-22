@extends('layouts.app')
@section('title', $kelas->nama_kelas)

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="bg-blue-600 p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">{{ $kelas->nama_kelas }}</h1>
        <p class="opacity-90">Instruktur: {{ $kelas->instruktur->username }}</p>
    </div>

    <div class="p-8">
        <h3 class="text-xl font-bold mb-4">Tentang Kelas</h3>
        <p class="text-gray-700 leading-relaxed mb-8 whitespace-pre-line">{{ $kelas->deskripsi }}</p>

        <div class="border-t pt-6">
            @if($statusPendaftaran)
                @if($statusPendaftaran->status == 'approved')
                    <div class="bg-green-100 text-green-800 p-4 rounded text-center font-bold">
                        ✅ Anda sudah terdaftar di kelas ini.
                    </div>
                @elseif($statusPendaftaran->status == 'pending')
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded text-center font-bold">
                        ⏳ Pendaftaran Anda sedang menunggu persetujuan instruktur.
                    </div>
                @elseif($statusPendaftaran->status == 'rejected')
                    <div class="bg-red-100 text-red-800 p-4 rounded text-center font-bold">
                        ❌ Pendaftaran Anda ditolak oleh instruktur.
                    </div>
                @endif
            @else
                <div class="flex items-center justify-between bg-gray-50 p-6 rounded">
                    <div>
                        <p class="text-gray-600">Tertarik mengikuti kelas ini?</p>
                        <p class="font-bold text-lg">Klik tombol daftar sekarang!</p>
                    </div>
                    <form action="{{ route('peserta.kelas.daftar', $kelas->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded font-bold text-lg hover:bg-blue-700 transition shadow-lg">
                            Daftar Kelas
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
