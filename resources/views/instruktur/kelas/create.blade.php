@extends('layouts.app')
@section('title', 'Buat Kelas')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Buat Kelas Baru</h2>
        <form action="{{ route('instruktur.kelas.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Kelas</label>
                <input type="text" name="nama_kelas" class="w-full border p-2 rounded" required
                    placeholder="Contoh: Belajar Laravel Dasar">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="w-full border p-2 rounded" required
                    placeholder="Jelaskan materi apa yang akan dipelajari..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="w-full border p-2 rounded" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Status Kelas</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="aktif">Aktif (Bisa Didaftar)</option>
                    <option value="non_aktif">Non Aktif (Disembunyikan)</option>
                    <option value="discontinued">Discontinued (Ditutup)</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Kelas</button>
        </form>
    </div>
@endsection
