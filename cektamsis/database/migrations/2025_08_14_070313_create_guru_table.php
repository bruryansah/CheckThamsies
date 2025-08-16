<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->unsignedBigInteger('user_id');
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->unsignedBigInteger('id_kelas')->nullable();
            $table->unsignedBigInteger('id_jurusan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru');
    }
};
