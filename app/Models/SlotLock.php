<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotLock extends Model
{
    use HasFactory;

    protected $fillable = ['slot_waktu_id', 'user_id', 'locked_at', 'expired_at'];

    protected $casts = [
        'locked_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function slotWaktu()
    {
        return $this->belongsTo(SlotWaktu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    public function isActive(): bool
    {
        return !$this->isExpired();
    }

    public function getRemainingSecondsAttribute(): int
    {
        return max(0, now()->diffInSeconds($this->expired_at, false));
    }

    public function scopeActive($query)
    {
        return $query->where('expired_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<=', now());
    }
}
