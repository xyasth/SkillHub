# SkillHub - Sistem Manajemen Kursus & Pelatihan

![Laravel](https://img.shields.io/badge/Laravel-11-red) ![PHP](https://img.shields.io/badge/PHP-8.2-blue) ![MySQL](https://img.shields.io/badge/Database-MySQL-orange) ![Tailwind](https://img.shields.io/badge/CSS-Tailwind-38B2AC) ![Tests](https://img.shields.io/badge/Tests-PASS-brightgreen)

**SkillHub** adalah aplikasi web berbasis **MVC (Model-View-Controller)** yang dibangun untuk mempermudah pengelolaan data kursus, instruktur, dan pendaftaran peserta yang sebelumnya dilakukan secara manual.

Proyek ini dikembangkan sebagai bagian dari **Uji Kompetensi Sertifikasi Programmer (LSP)**.

---

## Teknologi (Tech Stack)

Aplikasi ini dibangun menggunakan teknologi modern standar industri:

-   **Backend Framework:** Laravel 11 (PHP 8.2)
-   **Database:** MySQL (Relational Database)
-   **Frontend:** Blade Templating Engine + Tailwind CSS
-   **Testing:** PHPUnit (Automated Unit & Feature Testing)
-   **Architecture:** Monolithic MVC

---

## Fitur Utama

Sistem ini memiliki 3 hak akses (Role) dengan fungsionalitas berbeda:

### 1. Admin (Superuser)
-   **Dashboard Statistik:** Melihat ringkasan total user dan kelas.
-   **Manajemen User:** CRUD (Create, Read, Update, Delete) data Instruktur dan Peserta.
-   **Manajemen Kelas:** Memiliki akses penuh untuk menghapus atau mengelola kelas manapun.
-   **Monitoring:** Melihat seluruh riwayat pendaftaran.

### 2. Instruktur
-   **Manajemen Kelas:** Membuat, mengedit, dan menutup kelas yang diajar.
-   **Validasi Peserta:** Menerima (*Approve*) atau Menolak (*Reject*) pendaftaran masuk.
-   **Info Peserta:** Melihat daftar siswa yang terdaftar di kelasnya.

### 3. Peserta
-   **Katalog Kelas:** Melihat daftar kelas yang tersedia (Status Aktif).
-   **Pendaftaran:** Mendaftar ke kelas yang diminati.
-   **Manajemen Jadwal:** Membatalkan pendaftaran (*Unenroll*) jika status masih pending.
-   **Dashboard:** Melihat kelas yang sedang diikuti.

---

## Instalasi & Cara Menjalankan

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal:

### Prasyarat
-   PHP >= 8.2
-   Composer
-   MySQL / MariaDB

### Langkah-Langkah

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/USERNAME_GITHUB_KAMU/skillhub-project.git](https://github.com/USERNAME_GITHUB_KAMU/skillhub-project.git)
    cd skillhub-project
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    -   Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```
    -   Buka file `.env` dan sesuaikan konfigurasi database:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=skillhub_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Setup Database (Migrate & Seed)**
    Jalankan perintah ini untuk membuat tabel dan mengisi data dummy (Admin, Instruktur, Peserta, & Kelas):
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Buka browser dan akses: `http://127.0.0.1:8000`

---

## Pengujian (Testing)

Aplikasi ini telah melewati pengujian otomatis (**Grey-Box Testing**) menggunakan PHPUnit untuk memastikan validasi dan logika bisnis berjalan benar.

Untuk menjalankan test:
```bash
php artisan test
