<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Menambahkan status: aktif, non_aktif, discontinued
            $table->enum('status', ['aktif', 'non_aktif', 'discontinued'])->default('aktif')->after('deskripsi');

            // Menambahkan waktu (opsional, tapi sangat disarankan)
            $table->date('tgl_mulai')->nullable()->after('status');
            $table->date('tgl_selesai')->nullable()->after('tgl_mulai');
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['status', 'tgl_mulai', 'tgl_selesai']);
        });
    }
};
