<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cicilan;
use Illuminate\Http\Request;
use App\Models\Pinjaman;
use Carbon\Carbon;

class PinjamanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nominal_pinjam' => 'required|numeric|min:100000',
            'tenor_bulan' => 'required|integer|min:1',
            'bunga_persen' => 'required|numeric'
        ]);

        $pinjaman = Pinjaman::create([
            'user_id' => $request->user()->id,
            'nominal_pinjam' => $request->nominal_pinjam,
            'tenor_bulan' => $request->tenor_bulan,
            'bunga_persen' => $request->bunga_persen,
            'status_approval' => 'pending'
        ]);

        return response()->json([
            'message' => 'Pengajuan pinjaman berhasil dibuat, menunggu persetujuan',
            'data' => $pinjaman
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin' || $user->role === 'pengurus') {
            $pinjaman = Pinjaman::with(['user', 'cicilans'])->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar semua pengajuan pinjaman anggota (Mode Manajemen)',
                'data' => $pinjaman
            ], 200);
        }

        $pinjaman = Pinjaman::with('cicilans')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar histori pinjaman Anda',
            'data' => $pinjaman
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input, status harus diisi antara 'approved' atau 'rejected'
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // Cari data pinjaman berdasarkan ID
        $pinjaman = Pinjaman::find($id);

        // Cek apakah data ada dan statusnya masih 'pending'
        if (!$pinjaman || $pinjaman->status_approval !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Data pinjaman tidak ditemukan atau sudah tidak berstatus pending.'
            ], 404);
        }

        if ($request->status === 'approved') {

            $pinjaman->update([
                'status_approval' => 'approved',
                'tanggal_approval' => Carbon::now()->toDateString()
            ]);

            $pokok_per_bulan = $pinjaman->nominal_pinjam / $pinjaman->tenor_bulan;
            $bunga_per_bulan = ($pinjaman->nominal_pinjam * ($pinjaman->bunga_persen / 100)) / $pinjaman->tenor_bulan;
            $tagihan_per_bulan = $pokok_per_bulan + $bunga_per_bulan;

            for ($i = 1; $i <= $pinjaman->tenor_bulan; $i++) {
                Cicilan::create([
                    'pinjaman_id' => $pinjaman->id,
                    'bulan_ke' => $i,
                    'nominal_tagihan' => $tagihan_per_bulan,
                    'tanggal_jatuh_tempo' => Carbon::now()->addMonths($i)->toDateString(),
                    'status_lunas' => 'belum_lunas',
                    'denda' => 0
                ]);
            }

            $pesan = 'Pinjaman berhasil disetujui! Jadwal cicilan otomatis dibuat.';

        } else {
            $pinjaman->update([
                'status_approval' => 'rejected'
            ]);

            $pesan = 'Pengajuan pinjaman berhasil ditolak.';
        }

        // Ambil data terbaru beserta relasi cicilannya untuk dikembalikan ke Postman
        $pinjaman_update = Pinjaman::with('cicilans')->find($id);

        return response()->json([
            'success' => true,
            'message' => $pesan,
            'data' => $pinjaman_update
        ], 200);
    }

    public function getDashboardStats()
    {
        $stats = [
            'total_pinjaman_pending'   => Pinjaman::where('status_approval', 'pending')->count(),
            'total_pinjaman_approved'  => Pinjaman::where('status_approval', 'approved')->count(),
            'total_pinjaman_lunas'     => Pinjaman::where('status_approval', 'lunas')->count(),
            'total_dana_disalurkan'    => Pinjaman::whereIn('status_approval', ['approved', 'lunas'])->sum('nominal_pinjam'),
            'total_anggota_terdaftar'  => \App\Models\User::where('role', 'anggota')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Ringkasan statistik Koperasi Digital',
            'data'    => $stats
        ], 200);
    }
}
