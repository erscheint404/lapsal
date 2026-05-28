<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'status_lama', 'status_baru',
        'catatan', 'changed_by',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
