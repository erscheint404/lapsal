<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating_lapangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->text('ulasan')->nullable();
            $table->timestamps();

            $table->index('lapangan_id');
            $table->index('user_id');
            $table->unique(['booking_id']); // satu rating per booking
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_lapangan');
    }
};
