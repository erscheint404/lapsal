<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'avatar',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // === Relationships ===

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function detailMember()
    {
        return $this->hasOne(DetailMember::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(RatingLapangan::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function unreadNotifikasi()
    {
        return $this->notifikasi()->where('is_read', false);
    }

    // === Helpers ===

    public function isAdmin(): bool
    {
        return $this->role->slug === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role->slug === 'petugas';
    }

    public function isMember(): bool
    {
        return $this->role->slug === 'member';
    }

    public function isAdminOrPetugas(): bool
    {
        return in_array($this->role->slug, ['admin', 'petugas']);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=059669&color=fff&size=128';
    }
}
