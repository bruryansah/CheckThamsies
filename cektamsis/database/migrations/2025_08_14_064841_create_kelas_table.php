<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->unsignedBigInteger('id_jurusan');
            $table->string('nama_kelas', 100);
            $table->timestamps();

            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};
