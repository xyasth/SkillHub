@extends('layouts.app')
@section('title', 'Dashboard Peserta')

@section('content')
<div class="mb-8 bg-blue-50 p-6 rounded-lg flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-blue-800">Selamat Belajar, {{ Auth::user()->username }}!</h1>
        <p class="text-blue-600">Berikut adalah kelas yang sedang Anda ikuti.</p>
    </div>
    <a href="{{ route('peserta.kelas.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">Cari Kelas Baru</a>
</div>

<h2 class="text-xl font-bold mb-4">Kelas Saya (Approved)</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($kelasDiikuti as $p)
    <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-5">
            <h3 class="font-bold text-lg mb-2">{{ $p->kelas->nama_kelas }}</h3>
            <p class="text-sm text-gray-500 mb-4">Instruktur: {{ $p->kelas->instruktur->username }}</p>
            <div class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded inline-block mb-4">
                Status: Aktif
            </div>
            <a href="{{ route('peserta.kelas.show', $p->kelas_id) }}" class="block text-center bg-gray-800 text-white py-2 rounded hover:bg-gray-900">Masuk Kelas</a>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 bg-white rounded shadow">
        <p class="text-gray-500 mb-4">Anda belum mengikuti kelas apapun.</p>
        <a href="{{ route('peserta.kelas.index') }}" class="text-blue-600 font-bold hover:underline">Mulai Cari Kelas Sekarang &rarr;</a>
    </div>
    @endforelse
</div>
@endsection
