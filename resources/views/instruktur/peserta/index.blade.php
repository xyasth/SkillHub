@extends('layouts.app')

@section('title', 'Kelola Peserta')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Peserta di Kelas: {{ $kelas->nama_kelas }}</h1>
    <a href="{{ route('instruktur.dashboard') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead>
            <tr class="w-full bg-gray-800 text-white">
                <th class="py-3 px-4 text-left">Nama Peserta</th>
                <th class="py-3 px-4 text-left">Tanggal Daftar</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse($pendaftarans as $p)
            <tr class="border-b">
                <td class="py-3 px-4">{{ $p->user->username }} <br> <span class="text-xs text-gray-500">{{ $p->user->email }}</span></td>
                <td class="py-3 px-4">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="py-3 px-4">
                    @if($p->status == 'pending')
                        <span class="bg-yellow-200 text-yellow-800 py-1 px-2 rounded text-xs">Pending</span>
                    @elseif($p->status == 'approved')
                        <span class="bg-green-200 text-green-800 py-1 px-2 rounded text-xs">Approved</span>
                    @else
                        <span class="bg-red-200 text-red-800 py-1 px-2 rounded text-xs">Rejected</span>
                    @endif
                </td>
                <td class="py-3 px-4">
                    @if($p->status == 'pending')
                        <div class="flex space-x-2">
                            <form action="{{ route('instruktur.pendaftaran.update', $p->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-xs">✔ Terima</button>
                            </form>

                            <form action="{{ route('instruktur.pendaftaran.update', $p->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-xs">✖ Tolak</button>
                            </form>
                        </div>
                    @else
                        <span class="text-gray-400 text-xs italic">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Belum ada pendaftar di kelas ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
