<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans'; 
    
    // PERBAIKAN: Ubah dari id_pembayaran menjadi id sesuai kenyataan di database kamu
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'id_murid',
        'periode_id', 
        'nominal',
        'tipe',
        'tanggal_bayar',
        'keterangan'
    ];

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'id_murid', 'id_murid');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
}