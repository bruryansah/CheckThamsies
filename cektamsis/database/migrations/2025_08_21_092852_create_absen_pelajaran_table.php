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
            $table->unsignedBigInteger('id_jadwal');   // relasi ke jadwal
            $table->unsignedBigInteger('id_siswa');    // relasi ke siswa
            $table->dateTime('waktu_scan');            // waktu absen
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
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
