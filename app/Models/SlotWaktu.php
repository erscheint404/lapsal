<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotWaktu extends Model
{
    use HasFactory;

    protected $table = 'slot_waktu';

    protected $fillable = [
        'lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // === Relationships ===

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function slotLock()
    {
        return $this->hasOne(SlotLock::class, 'slot_waktu_id');
    }

    public function activeLock()
    {
        return $this->hasOne(SlotLock::class, 'slot_waktu_id')
            ->where('expired_at', '>', now());
    }

    // === Scopes ===

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeForDate($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    public function scopeForLapangan($query, $lapanganId)
    {
        return $query->where('lapangan_id', $lapanganId);
    }

    // === Helpers ===

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }

    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    public function getWaktuAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'reserved' => 'Direservasi',
            'booked' => 'Sudah Dipesan',
            'blocked' => 'Diblokir',
            default => $this->status,
        };
    }
}
