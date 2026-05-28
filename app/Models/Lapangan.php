<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';

    protected $fillable = [
        'nama', 'deskripsi', 'harga_per_jam', 'fasilitas',
        'tipe', 'status', 'foto_utama',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'harga_per_jam' => 'decimal:2',
    ];

    // === Relationships ===

    public function fotoLapangan()
    {
        return $this->hasMany(FotoLapangan::class)->orderBy('urutan');
    }

    public function slotWaktu()
    {
        return $this->hasMany(SlotWaktu::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(RatingLapangan::class);
    }

    // === Scopes ===

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // === Accessors ===

    public function getRataRatingAttribute(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    public function getJumlahRatingAttribute(): int
    {
        return $this->ratings()->count();
    }

    public function getFotoUtamaUrlAttribute(): string
    {
        if ($this->foto_utama) {
            return asset('storage/' . $this->foto_utama);
        }
        return asset('images/default-lapangan.jpg');
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_jam, 0, ',', '.');
    }

    public function getFasilitasListAttribute(): array
    {
        return $this->fasilitas ?? [];
    }
}
