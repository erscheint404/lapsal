<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoLapangan extends Model
{
    use HasFactory;

    protected $table = 'foto_lapangan';

    protected $fillable = ['lapangan_id', 'path', 'urutan'];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
