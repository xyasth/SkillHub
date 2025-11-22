<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pendaftaran
 * * Merepresentasikan tabel pivot (Many-to-Many) antara Peserta dan Kelas.
 * Menyimpan status persetujuan (pending, approved, rejected).
 * * @package App\Models
 */
class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'status',
        'tanggal_daftar'
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    /**
     * Relasi: Pendaftaran milik satu User (Peserta).
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Pendaftaran tertuju pada satu Kelas.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
