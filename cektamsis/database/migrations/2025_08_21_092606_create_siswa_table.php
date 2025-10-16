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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->unsignedBigInteger('user_id');
            $table->string('nisn', 20)->unique();
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_jurusan');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
