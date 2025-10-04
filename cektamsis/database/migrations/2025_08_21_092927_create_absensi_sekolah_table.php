<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi_sekolah', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('id_siswa');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('latitude_in', 50)->nullable();
            $table->string('longitude_in', 50)->nullable();
            $table->string('latitude_out', 50)->nullable();
            $table->string('longitude_out', 50)->nullable();
            $table->enum('status', ['hadir', 'izin','terlambat', 'sakit', 'alfa']);
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_sekolah');
    }
};
