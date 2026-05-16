<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $fillable = ['nama_periode', 'is_aktif'];

    public function pembayaran()
    {
        return $this->hasMany(pembayaran::class, 'periode_id');
    }
}