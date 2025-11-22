@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm uppercase">Total User</h3>
            <p class="text-3xl font-bold">{{ $totalUser }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm uppercase">Total Kelas</h3>
            <p class="text-3xl font-bold">{{ $totalKelas }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm uppercase">Total Pendaftaran</h3>
            <p class="text-3xl font-bold">{{ $pendaftarans->count() }}</p>
        </div>
    </div>

    <div class="mt-6 flex gap-4">
        <a href="{{ route('admin.users.index') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Manajemen Users</a>
        <a href="{{ route('admin.kelas.index') }}"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Manajemen Kelas</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden my-2">
        <div class="px-6 py-4 border-b">
            <h3 class="font-bold">Log Pendaftaran Terbaru</h3>
        </div>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left">Peserta</th>
                    <th class="py-2 px-4 text-left">Kelas</th>
                    <th class="py-2 px-4 text-left">Status</th>
                    <th class="py-2 px-4 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($pendaftarans as $p)
                    <tr>
                        <td class="py-2 px-4">{{ $p->user->username }}</td>
                        <td class="py-2 px-4">{{ $p->kelas->nama_kelas }}</td>
                        <td class="py-2 px-4">
                            <span
                                class="px-2 py-1 rounded text-xs text-white
                        {{ $p->status == 'approved' ? 'bg-green-500' : ($p->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="py-2 px-4">{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
