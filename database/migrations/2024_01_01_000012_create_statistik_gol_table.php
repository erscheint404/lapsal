<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistik_gol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('nama_pemain', 100);
            $table->integer('jumlah_gol')->default(0);
            $table->timestamps();

            $table->index('booking_id');
            $table->index('nama_pemain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistik_gol');
    }
};
