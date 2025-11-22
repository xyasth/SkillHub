@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Users</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:underline">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 rounded shadow mb-8">
        <h3 class="font-bold mb-4 text-lg">Tambah User Baru</h3>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Ups! Ada kesalahan:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        </form>
        <form action="{{ route('admin.users.store') }}" method="POST"
            class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm mb-1">Username</label>
                <input type="text" name="username" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Role</label>
                <select name="role" class="w-full border p-2 rounded">
                    <option value="peserta">Peserta</option>
                    <option value="instruktur">Instruktur</option>
                </select>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 h-10">Tambah</button>
        </form>
    </div>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Username</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Role</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($users as $u)
                    <tr>
                        <td class="py-3 px-4">{{ $u->username }}</td>
                        <td class="py-3 px-4">{{ $u->email }}</td>
                        <td class="py-3 px-4"><span
                                class="bg-gray-200 px-2 py-1 rounded text-sm">{{ ucfirst($u->role) }}</span></td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus user ini?');">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
