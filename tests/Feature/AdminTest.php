<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat akun admin untuk dipakai di setiap test
        $this->admin = User::create([
            'username' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }

    public function test_admin_bisa_melihat_list_user()
    {
        $this->actingAs($this->admin)
            ->get('/admin/users')
            ->assertStatus(200)
            ->assertSee('Manajemen Users');
    }

    public function test_admin_bisa_tambah_user_baru()
    {
        $response = $this->actingAs($this->admin)->post('/admin/users', [
            'username' => 'UserBaru',
            'email' => 'baru@test.com',
            'password' => '123456',
            'role' => 'peserta'
        ]);

        $this->assertDatabaseHas('users', ['email' => 'baru@test.com']);
        $response->assertSessionHas('success');
    }

    public function test_admin_bisa_hapus_user()
    {
        $user = User::create(['username' => 'Dihapus', 'email' => 'hapus@test.com', 'password' => '123', 'role' => 'peserta']);

        $this->actingAs($this->admin)->delete("/admin/users/{$user->id}");

        $this->assertDatabaseMissing('users', ['email' => 'hapus@test.com']);
    }

    public function test_admin_bisa_buat_kelas_untuk_instruktur()
    {
        $instruktur = User::create(['username' => 'Guru', 'email' => 'guru@test.com', 'password' => '123', 'role' => 'instruktur']);

        $response = $this->actingAs($this->admin)->post('/admin/kelas', [
            'nama_kelas' => 'Kelas Admin',
            'deskripsi' => 'Dibuat oleh admin',
            'instructor_id' => $instruktur->id
        ]);

        $this->assertDatabaseHas('kelas', ['nama_kelas' => 'Kelas Admin']);
    }
}
