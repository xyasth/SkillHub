@extends('layouts.app')
@section('title', 'Daftar Kelas')

@section('content')
<h1 class="text-2xl font-bold mb-6">Katalog Kelas Tersedia</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($semuaKelas as $kelas)
    <div class="bg-white rounded shadow hover:shadow-lg transition flex flex-col h-full">
        <div class="p-5 flex-1">
            <h3 class="font-bold text-lg mb-2">{{ $kelas->nama_kelas }}</h3>
            <p class="text-sm text-gray-500 mb-4">Oleh: {{ $kelas->instruktur->username }}</p>
            <p class="text-gray-700 text-sm line-clamp-3">{{ $kelas->deskripsi }}</p>
        </div>
        <div class="p-5 border-t bg-gray-50">
            <a href="{{ route('peserta.kelas.show', $kelas->id) }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
    </div>
    @endforeach
</div>
@endsection
