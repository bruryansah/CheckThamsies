<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guru_mapel_kelas', function (Blueprint $table) {
            $table->id('id_gmk');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_kelas');
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru_mapel_kelas');
    }
};
