<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SimpananController extends Controller
{
    // Anggota melakukan setoran simpanan
    public function store(Request $request) {
        $request->validate([
            'jenis_simpanan' => 'required|in:pokok,wajib,sukarela',
            'nominal' => 'required|numeric|min:10000',
        ]);

        $simpanan = Simpanan::create([
            'user_id' => $request->user()->id,
            'jenis_simpanan' => $request->jenis_simpanan,
            'nominal' => $request->nominal,
            'tanggal_setor' => Carbon::now()->toDateString()
        ]);

        return response()->json(['success' => true, 'message' => 'Simpanan berhasil dicatat!', 'data' => $simpanan]);
    }

    // Lihat total saldo simpanan anggota
    public function index(Request $request) {
        $simpanan = Simpanan::where('user_id', $request->user()->id)->get();
        $total = $simpanan->sum('nominal');

        return response()->json([
            'success' => true,
            'total_saldo' => $total,
            'data' => $simpanan
        ]);
    }
}
