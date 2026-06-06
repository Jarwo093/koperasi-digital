<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cicilan extends Model
{
    use HasFactory;

    protected $table = 'cicilan';

    protected $fillable = [
        'pinjaman_id',
        'bulan_ke',
        'nominal_tagihan',
        'tanggal_jatuh_tempo',
        'status_lunas',
        'denda'
    ];

    public function transaksi() {
        return $this->hasMany(Transaksi::class);
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}
