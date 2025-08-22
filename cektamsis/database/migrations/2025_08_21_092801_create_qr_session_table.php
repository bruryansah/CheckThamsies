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
        Schema::create('qr_session', function (Blueprint $table) {
            $table->id('id_qr');
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_guru');
            $table->string('kode_qr', 255)->unique();
            $table->string('nama_mapel', 255);
            $table->string('nama_kelas', 255);
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->enum('status', ['aktif', 'expired']);
            $table->timestamps();

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal');
            $table->foreign('id_guru')->references('id_guru')->on('guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_session');
    }
};
