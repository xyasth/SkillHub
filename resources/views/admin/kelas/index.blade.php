@extends('layouts.app')
@section('title', 'Manajemen Kelas')

@section('content')
<h1 class="text-2xl font-bold mb-6">Manajemen Kelas (Admin)</h1>

<div class="bg-white p-6 rounded shadow mb-8">
    <h3 class="font-bold mb-4">Buat Kelas Baru</h3>
    <form action="{{ route('admin.kelas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <div class="md:col-span-2">
            <label class="block text-sm">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="w-full border p-2 rounded" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border p-2 rounded" rows="2"></textarea>
        </div>
        <div>
            <label class="block text-sm">Pilih Instruktur</label>
            <select name="instructor_id" class="w-full border p-2 rounded">
                @foreach($instrukturs as $ins)
                    <option value="{{ $ins->id }}">{{ $ins->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full">Simpan Kelas</button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($kelas as $k)
    <div class="bg-white p-4 rounded shadow relative">
        <h3 class="font-bold text-lg">{{ $k->nama_kelas }}</h3>
        <p class="text-sm text-gray-500 mb-2">Instruktur: {{ $k->instruktur->username }}</p>
        <p class="text-gray-700 text-sm mb-4">{{ Str::limit($k->deskripsi, 80) }}</p>

        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="absolute top-4 right-4" onsubmit="return confirm('Hapus kelas ini?');">
            @csrf @method('DELETE')
            <button class="text-red-500 hover:text-red-700">🗑</button>
        </form>
    </div>
    @endforeach
</div>
@endsection
