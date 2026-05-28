<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_waktu_id')->constrained('slot_waktu')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('locked_at');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->index(['slot_waktu_id', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_locks');
    }
};
