<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('lapangan_id')->constrained('lapangan')->onDelete('restrict');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_jam')->default(1);
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', [
                'draft', 'pending_payment', 'under_review',
                'waiting_confirmation', 'confirmed', 'rejected',
                'cancelled', 'expired', 'completed'
            ])->default('draft');
            $table->enum('metode_pembayaran', ['midtrans', 'manual'])->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('idempotency_key')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('lapangan_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index('kode_booking');
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
