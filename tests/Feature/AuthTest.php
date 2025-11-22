<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_bisa_diakses()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_tidak_bisa_akses_dashboard_tanpa_login()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_redirect_admin()
    {
        $user = User::create([
            'username' => 'Admin', 'email' => 'admin@test.com',
            'password' => bcrypt('password'), 'role' => 'admin'
        ]);

        $response = $this->post('/login', ['email' => 'admin@test.com', 'password' => 'password']);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_login_redirect_peserta()
    {
        $user = User::create([
            'username' => 'Peserta', 'email' => 'peserta@test.com',
            'password' => bcrypt('password'), 'role' => 'peserta'
        ]);

        $response = $this->post('/login', ['email' => 'peserta@test.com', 'password' => 'password']);
        $response->assertRedirect('/peserta/dashboard');
    }
}
