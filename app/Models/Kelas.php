<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas'; // Definisikan nama tabel karena singular bukan 'kelas'
    protected $guarded = [];

    // Relasi: Kelas dimiliki satu Instruktur
    public function instruktur()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Relasi: Kelas punya banyak pendaftaran
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'kelas_id');
    }
    protected $fillable = [
        'instructor_id',
        'nama_kelas',
        'deskripsi',
        'status',      // <--- Baru
        'tgl_mulai',   // <--- Baru
        'tgl_selesai', // <--- Baru
    ];

    // Agar tanggal otomatis jadi objek Carbon (mudah diformat)
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];
}
