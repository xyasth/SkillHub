<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PesertaTest extends TestCase
{
    use RefreshDatabase;

    private $peserta;
    private $kelas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->peserta = User::create([
            'username' => 'Andi', 'email' => 'andi@test.com',
            'password' => bcrypt('password'), 'role' => 'peserta'
        ]);

        $instruktur = User::create(['username' => 'Guru', 'email' => 'guru@test.com', 'password' => '123', 'role' => 'instruktur']);

        $this->kelas = Kelas::create([
            'instructor_id' => $instruktur->id, 'nama_kelas' => 'Kelas PHP',
            'deskripsi' => 'Belajar Yuk', 'status' => 'aktif',
            'tgl_mulai' => now(), 'tgl_selesai' => now()->addDays(30)
        ]);
    }

    public function test_peserta_bisa_melihat_katalog_kelas()
    {
        $this->actingAs($this->peserta)
             ->get('/peserta/kelas')
             ->assertStatus(200)
             ->assertSee('Kelas PHP');
    }

    public function test_peserta_bisa_daftar_kelas()
    {
        $response = $this->actingAs($this->peserta)
                         ->post("/peserta/kelas/{$this->kelas->id}/daftar");

        $this->assertDatabaseHas('pendaftarans', [
            'user_id' => $this->peserta->id,
            'kelas_id' => $this->kelas->id,
            'status' => 'pending'
        ]);

        $response->assertRedirect('/peserta/dashboard');
    }

    public function test_peserta_bisa_batal_daftar()
    {
        // Daftar dulu
        Pendaftaran::create([
            'user_id' => $this->peserta->id, 'kelas_id' => $this->kelas->id, 'status' => 'pending'
        ]);

        // Batal
        $this->actingAs($this->peserta)
             ->delete("/peserta/kelas/{$this->kelas->id}/batal");

        $this->assertDatabaseMissing('pendaftarans', [
            'user_id' => $this->peserta->id,
            'kelas_id' => $this->kelas->id
        ]);
    }
}
