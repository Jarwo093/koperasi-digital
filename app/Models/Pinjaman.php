<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'user_id',
        'nominal_pinjam',
        'tenor_bulan',
        'bunga_persen',
        'status_approval',
        'tanggal_approval',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cicilans()
    {
        return $this->hasMany(Cicilan::class);
    }
}
