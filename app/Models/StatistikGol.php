<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikGol extends Model
{
    use HasFactory;

    protected $table = 'statistik_gol';

    protected $fillable = ['booking_id', 'nama_pemain', 'jumlah_gol'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
