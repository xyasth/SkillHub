<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kelas
 * * Merepresentasikan data kelas/kursus yang tersedia.
 * * @package App\Models
 */
class Kelas extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * * @var string
     */
    protected $table = 'kelas';

    protected $fillable = [
        'instructor_id',
        'nama_kelas',
        'deskripsi',
        'status',      // aktif, non_aktif, discontinued
        'tgl_mulai',
        'tgl_selesai',
    ];

    /**
     * Konversi otomatis tipe data kolom tanggal menjadi objek Carbon.
     * Memudahkan format tanggal di View.
     * * @var array
     */
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    /**
     * Relasi: Kelas dimiliki oleh satu Instruktur (User).
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instruktur()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Relasi: Kelas memiliki banyak data pendaftaran peserta.
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'kelas_id');
    }
}
