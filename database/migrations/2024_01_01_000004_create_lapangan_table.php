<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_jam', 12, 2);
            $table->json('fasilitas')->nullable();
            $table->enum('tipe', ['vinyl', 'rumput_sintetis', 'semen', 'parquette'])->default('rumput_sintetis');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('foto_utama')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};
