<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pembayaran extends Model
{
    //
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = ['id_murid', 'nominal', 'tipe', 'tanggal_bayar', 'keterangan'];

        public function murid()
    {
        return $this->belongsTo(Murid::class, 'id_murid', 'id_murid');
    }
}
