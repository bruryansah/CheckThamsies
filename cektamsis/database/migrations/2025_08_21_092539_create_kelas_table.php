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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 100);
            $table->string('tingkat_kelas', 11);
            $table->string('total_siswa', 11);
            $table->unsignedBigInteger('id_jurusan');
            $table->unsignedBigInteger('id_wali_kelas');
            $table->timestamps();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
            $table->foreign('id_wali_kelas')->references('id_guru')->on('guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
