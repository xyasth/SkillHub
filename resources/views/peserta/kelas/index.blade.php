@extends('layouts.app')
@section('title', 'Daftar Kelas')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Katalog Kelas Tersedia</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($semuaKelas as $kelas)
            <div class="bg-white rounded shadow hover:shadow-lg transition flex flex-col h-full border">
                <div class="p-5 flex-1">
                    <h3 class="font-bold text-lg mb-2 text-blue-800">{{ $kelas->nama_kelas }}</h3>
                    <p class="text-sm text-gray-500 mb-4 font-medium">
                        👨‍🏫 Instruktur: {{ $kelas->instruktur->username }}
                    </p>
                    <p class="text-gray-700 text-sm line-clamp-3 mb-4">
                        {{ Str::limit($kelas->deskripsi, 100) }}
                    </p>
                </div>
                <div class="p-5 flex-1">
                    <p class="text-xs text-gray-500 mb-2">
                        📅 Periode: {{ $kelas->tgl_mulai ? $kelas->tgl_mulai->format('d M Y') : '-' }}
                        s/d {{ $kelas->tgl_selesai ? $kelas->tgl_selesai->format('d M Y') : '-' }}
                    </p>

                    <p class="text-gray-700 text-sm line-clamp-3 mb-4">
                        {{ Str::limit($kelas->deskripsi, 100) }}
                    </p>
                </div>
                <div class="p-4 border-t bg-gray-50">
                    <a href="{{ route('peserta.kelas.show', $kelas->id) }}"
                        class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition font-semibold">
                        Lihat Detail & Daftar
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded shadow">
                <p class="text-gray-500 text-lg">Belum ada kelas yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
@endsection
