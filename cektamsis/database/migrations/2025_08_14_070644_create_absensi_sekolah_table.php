<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
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
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_sekolah');
    }
};
