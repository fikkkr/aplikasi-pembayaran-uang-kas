<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    use HasFactory;

    protected $table = 'murids';
    protected $primaryKey = 'id_murid'; // harus sesuai dengan nama primary key di migrasi
    protected $fillable = ['nama', 'absen', 'kelas'];

    /**
     * Relasi: Satu murid bisa punya banyak catatan pembayaran
     */
    public function pembayaran()
    {
        // 'id_murid' adalah foreign key di tabel pembayaran
        return $this->hasMany(pembayaran::class, 'id_murid', 'id_murid');
    }
}