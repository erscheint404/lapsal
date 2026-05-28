<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingLapangan extends Model
{
    use HasFactory;

    protected $table = 'rating_lapangan';

    protected $fillable = [
        'lapangan_id', 'booking_id', 'user_id',
        'rating', 'ulasan',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
