<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelurahan')->unique();
            $table->string('nama_kelurahan');
            // Tambahkan kolom kunci asing yang terhubung ke tabel kecamatans
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};
