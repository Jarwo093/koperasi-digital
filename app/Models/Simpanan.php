<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;
    protected $table = 'simpanan';
    protected $fillable = ['user_id', 'jenis_simpanan', 'nominal', 'tanggal_setor'];
}
