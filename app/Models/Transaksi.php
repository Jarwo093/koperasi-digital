<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = ['cicilan_id', 'nominal_bayar', 'tanggal_bayar', 'metode_pembayaran'];

    public function cicilan() {
        return $this->belongsTo(Cicilan::class);
    }
}
