<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi_pelajaran', function (Blueprint $table) {
            $table->id('id_absensi_pelajaran');
            $table->unsignedBigInteger('id_qr');
            $table->unsignedBigInteger('id_siswa');
            $table->dateTime('waktu_scan');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_qr')->references('id_qr')->on('qr_session');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_pelajaran');
    }
};
