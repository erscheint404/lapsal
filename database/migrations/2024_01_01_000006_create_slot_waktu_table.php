<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_waktu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['available', 'reserved', 'booked', 'blocked'])->default('available');
            $table->timestamps();

            $table->index(['lapangan_id', 'tanggal']);
            $table->index('status');
            $table->unique(['lapangan_id', 'tanggal', 'jam_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_waktu');
    }
};
