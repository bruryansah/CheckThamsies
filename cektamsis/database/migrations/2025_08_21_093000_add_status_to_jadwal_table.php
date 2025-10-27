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
        Schema::table('jadwal', function (Blueprint $table) {
            // Tambah kolom status jika belum ada
            if (!Schema::hasColumn('jadwal', 'status')) {
                $table->enum('status', ['aktif', 'tutup'])->default('aktif')->after('jam_selesai');
            }
            
            // Tambah kolom last_finalized_at jika belum ada
            if (!Schema::hasColumn('jadwal', 'last_finalized_at')) {
                $table->dateTime('last_finalized_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('jadwal', 'last_finalized_at')) {
                $table->dropColumn('last_finalized_at');
            }
        });
    }
};
