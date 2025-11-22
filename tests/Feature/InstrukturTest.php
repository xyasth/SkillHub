<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstrukturTest extends TestCase
{
    use RefreshDatabase;

    private $instruktur;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instruktur = User::create([
            'username' => 'Pak Guru', 'email' => 'guru@test.com',
            'password' => bcrypt('password'), 'role' => 'instruktur'
        ]);
    }

    public function test_instruktur_bisa_buat_kelas()
    {
        $response = $this->actingAs($this->instruktur)->post('/instruktur/kelas', [
            'nama_kelas' => 'Belajar PHP',
            'deskripsi' => 'Materi Dasar',
            'status' => 'aktif',
            'tgl_mulai' => '2024-01-01',
            'tgl_selesai' => '2024-02-01',
        ]);

        $this->assertDatabaseHas('kelas', ['nama_kelas' => 'Belajar PHP']);
    }

    public function test_instruktur_bisa_approve_peserta()
    {
        // 1. Setup Data
        $kelas = Kelas::create([
            'instructor_id' => $this->instruktur->id, 'nama_kelas' => 'Test Kelas',
            'deskripsi' => 'Desc', 'status' => 'aktif', 'tgl_mulai' => now(), 'tgl_selesai' => now()
        ]);

        $peserta = User::create(['username' => 'Siswa', 'email' => 'siswa@test.com', 'password' => '123', 'role' => 'peserta']);

        $pendaftaran = Pendaftaran::create([
            'user_id' => $peserta->id, 'kelas_id' => $kelas->id, 'status' => 'pending'
        ]);

        // 2. Action: Approve
        $this->actingAs($this->instruktur)
             ->patch("/instruktur/pendaftaran/{$pendaftaran->id}", ['status' => 'approved']);

        // 3. Assert
        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'status' => 'approved'
        ]);
    }
}
