@extends('layouts.app')
@section('title', 'Dashboard Instruktur')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Kelas Saya</h1>
    <a href="{{ route('instruktur.kelas.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Buat Kelas Baru</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($kelasSaya as $kelas)
    <div class="bg-white rounded-lg shadow-md overflow-hidden border hover:shadow-lg transition">
        <div class="p-5">
            <h3 class="text-xl font-bold mb-2">{{ $kelas->nama_kelas }}</h3>
            <p class="text-gray-600 text-sm mb-4 h-12 overflow-hidden">{{ Str::limit($kelas->deskripsi, 100) }}</p>

            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <span>👥 {{ $kelas->pendaftarans_count }} Pendaftar</span>
            </div>

            <div class="flex flex-col space-y-2">
                <a href="{{ route('instruktur.kelas.peserta', $kelas->id) }}" class="bg-green-500 text-center text-white py-2 rounded hover:bg-green-600">
                    Lihat / Approve Peserta
                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('instruktur.kelas.edit', $kelas->id) }}" class="flex-1 bg-yellow-400 text-center text-white py-2 rounded hover:bg-yellow-500">Edit</a>
                    </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-10 text-gray-500">
        Anda belum memiliki kelas. Silakan buat kelas baru.
    </div>
    @endforelse
</div>
@endsection
