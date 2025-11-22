<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * * Merepresentasikan entitas pengguna dalam sistem (Admin, Instruktur, Peserta).
 * * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     * * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role', // admin, instruktur, peserta
    ];

    /**
     * Atribut yang harus disembunyikan untuk array output.
     * * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi: User (sebagai Instruktur) memiliki banyak kelas yang diajar.
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kelasAjar()
    {
        return $this->hasMany(Kelas::class, 'instructor_id');
    }

    /**
     * Relasi: User (sebagai Peserta) memiliki banyak riwayat pendaftaran kelas.
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'user_id');
    }

    /**
     * Helper untuk memeriksa peran pengguna.
     * * @param string $role Role yang dicek
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
