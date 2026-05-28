<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking', 'user_id', 'lapangan_id', 'tanggal',
        'jam_mulai', 'jam_selesai', 'durasi_jam', 'total_harga',
        'status', 'metode_pembayaran', 'midtrans_snap_token',
        'midtrans_order_id', 'idempotency_key', 'qr_code_path',
        'catatan_admin', 'alasan_penolakan', 'cancelled_at',
        'completed_at', 'confirmed_at', 'refund_amount', 'reschedule_fee',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'reschedule_fee' => 'decimal:2',
    ];

    // === Boot ===

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->kode_booking)) {
                $booking->kode_booking = 'BK-' . strtoupper(Str::random(8));
            }
            if (empty($booking->idempotency_key)) {
                $booking->idempotency_key = Str::uuid()->toString();
            }
        });
    }

    // === Relationships ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function buktiPembayaran()
    {
        return $this->hasOne(BuktiPembayaran::class);
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatBooking::class)->orderByDesc('created_at');
    }

    public function rating()
    {
        return $this->hasOne(RatingLapangan::class);
    }

    public function statistikGol()
    {
        return $this->hasMany(StatistikGol::class);
    }

    // === Scopes ===

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeToday($query)
    {
        return $query->where('tanggal', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', today())
            ->whereIn('status', ['confirmed', 'pending_payment', 'waiting_confirmation']);
    }

    // === Helpers ===

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getWaktuAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending_payment' => 'Menunggu Pembayaran',
            'under_review' => 'Sedang Diverifikasi',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            'completed' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'pending_payment' => 'warning',
            'under_review' => 'info',
            'waiting_confirmation' => 'info',
            'confirmed' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'danger',
            'expired' => 'gray',
            'completed' => 'primary',
            default => 'gray',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending_payment', 'under_review', 'waiting_confirmation', 'confirmed']);
    }

    public function canBeRated(): bool
    {
        return $this->status === 'completed' && !$this->rating;
    }

    public function getQrCodeUrlAttribute(): ?string
    {
        if ($this->qr_code_path) {
            return asset('storage/' . $this->qr_code_path);
        }
        return null;
    }

    public function getWhatsappShareUrlAttribute(): string
    {
        $text = "🏟️ *Booking Lapangan Futsal*\n";
        $text .= "📋 Kode: {$this->kode_booking}\n";
        $text .= "⚽ Lapangan: {$this->lapangan->nama}\n";
        $text .= "📅 Tanggal: {$this->tanggal->format('d M Y')}\n";
        $text .= "🕐 Waktu: {$this->waktu}\n";
        $text .= "💰 Total: {$this->formatted_harga}\n";
        $text .= "📌 Status: {$this->status_label}";

        return 'https://wa.me/?text=' . urlencode($text);
    }
}
