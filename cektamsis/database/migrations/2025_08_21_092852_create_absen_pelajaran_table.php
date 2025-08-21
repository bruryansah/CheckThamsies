<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi_pelajaran', function (Blueprint $table) {
            $table->id('id_absensi_pelajaran');
            $table->unsignedBigInteger('id_qr');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_guru');
            $table->dateTime('waktu_scan');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_qr')->references('id_qr')->on('qr_session');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal');
            $table->foreign('id_guru')->references('id_guru')->on('guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_pelajaran');
    }
};
