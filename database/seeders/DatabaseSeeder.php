<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ---------------------------------------
        // 1. BUAT SUPER ADMIN
        // ---------------------------------------
        User::create([
            'username' => 'Super Admin',
            'email' => 'admin@skillhub.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // ---------------------------------------
        // 2. BUAT 3 INSTRUKTUR
        // ---------------------------------------
        $instrukturs = [];
        for ($i = 1; $i <= 3; $i++) {
            $instrukturs[] = User::create([
                'username' => "Instruktur $i",
                'email' => "instruktur$i@skillhub.com",
                'password' => Hash::make('password123'),
                'role' => 'instruktur'
            ]);
        }

        // ---------------------------------------
        // 3. BUAT 5 KELAS (DENGAN STATUS & TANGGAL)
        // ---------------------------------------
        $namaKelasList = [
            'Dasar Pemrograman Web',
            'Desain Grafis dengan Photoshop',
            'Public Speaking Masterclass',
            'Digital Marketing 101',
            'Video Editing untuk Youtube'
        ];

        $allKelas = [];

        foreach ($namaKelasList as $index => $nama) {
            $randomInstruktur = $instrukturs[array_rand($instrukturs)];

            // Logika Tanggal:
            // Tanggal mulai bervariasi dari 30 hari lalu sampai 10 hari ke depan
            $tglMulai = Carbon::now()->subDays(rand(-10, 30));
            // Tanggal selesai 1 sampai 3 bulan setelah mulai
            $tglSelesai = (clone $tglMulai)->addMonths(rand(1, 3));

            // Logika Status:
            // Kita buat mayoritas 'aktif', sisanya acak
            $listStatus = ['aktif', 'aktif', 'aktif', 'non_aktif', 'discontinued'];
            $statusRandom = $listStatus[rand(0, 4)];

            $allKelas[] = Kelas::create([
                'instructor_id' => $randomInstruktur->id,
                'nama_kelas' => $nama,
                'deskripsi' => "Ini adalah deskripsi lengkap untuk kelas $nama. Pelajari skill baru sekarang juga!",
                'status' => $statusRandom,      // <--- Field Baru
                'tgl_mulai' => $tglMulai,       // <--- Field Baru
                'tgl_selesai' => $tglSelesai    // <--- Field Baru
            ]);
        }

        // ---------------------------------------
        // 4. BUAT 20 PESERTA
        // ---------------------------------------
        $pesertas = [];
        for ($i = 1; $i <= 20; $i++) {
            $pesertas[] = User::create([
                'username' => "Peserta $i",
                'email' => "peserta$i@skillhub.com",
                'password' => Hash::make('password123'),
                'role' => 'peserta'
            ]);
        }

        // ---------------------------------------
        // 5. BUAT PENDAFTARAN ACAK
        // ---------------------------------------
        foreach ($pesertas as $peserta) {
            // Ambil 1-3 kelas acak untuk didaftarkan
            $kelasAcak = collect($allKelas)->random(rand(1, 3));

            foreach ($kelasAcak as $kelas) {
                // Hanya daftar jika kelas TIDAK discontinued (logika realistis)
                if($kelas->status !== 'discontinued') {
                    Pendaftaran::create([
                        'user_id' => $peserta->id,
                        'kelas_id' => $kelas->id,
                        'status' => ['pending', 'approved', 'approved', 'rejected'][rand(0, 3)],
                        'tanggal_daftar' => Carbon::now()->subDays(rand(1, 15))
                    ]);
                }
            }
        }
    }
}
