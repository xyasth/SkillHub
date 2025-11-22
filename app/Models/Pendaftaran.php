<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Data Peserta
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id'); // Data Kelas
    }
}
