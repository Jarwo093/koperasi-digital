<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cicilan;
use App\Models\Transaksi;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function bayarCicilan(Request $request)
    {
        // Validasi input yang dikirim dari Postman
        $request->validate([
            'cicilan_id'        => 'required|exists:cicilan,id',
            'nominal_bayar'     => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:transfer,tunai'
        ]);

        // Cari data jadwal cicilan berdasarkan ID yang dipilih
        $cicilan = Cicilan::find($request->cicilan_id);

        // Validasi A: Cek apakah cicilan ini sebenarnya sudah lunas
        if ($cicilan->status_lunas === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Cicilan bulan ini sudah pernah dilunasi sebelumnya.'
            ], 400);
        }

        // Validasi B: Cek apakah uang yang dibayar kurang dari nominal tagihan
        if ($request->nominal_bayar < $cicilan->nominal_tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Nominal uang yang Anda masukkan kurang. Tagihan bulan ini sebesar Rp ' . number_format($cicilan->nominal_tagihan, 0, ',', '.')
            ], 400);
        }

        // Langkah 1: Buat record data baru di tabel transaksis
        $transaksi = Transaksi::create([
            'cicilan_id'        => $cicilan->id,
            'nominal_bayar'     => $request->nominal_bayar,
            'tanggal_bayar'     => Carbon::now()->toDateString(),
            'metode_pembayaran' => $request->metode_pembayaran
        ]);

        // Langkah 2: Ubah status lunas pada tabel cicilans menjadi 'lunas'
        $cicilan->update([
            'status_lunas' => 'lunas'
        ]);

        $pinjamanId = $cicilan->pinjaman_id;
        $sisaCicilanBelumLunas = Cicilan::where('pinjaman_id', $pinjamanId)->where('status_lunas', 'belum_lunas')->count();

        // Jika sudah tidak ada cicilan yang tersisa (0), otomatis pinjaman induknya kita set LUNAS
        if ($sisaCicilanBelumLunas === 0) {
            Pinjaman::where('id', $pinjamanId)->update([
                'status_approval' => 'lunas'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran cicilan berhasil diproses! Status tagihan diperbarui menjadi LUNAS.',
            'data'    => $transaksi
        ], 200);
    }

    public function cetakKuitansi($id)
    {
        $transaksi = Transaksi::with(['cicilan.pinjaman.user'])->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Data transaksi tidak ditemukan.'
            ], 404);
        }

        $data = [
            'nomor_kuitansi' => 'KW-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT),
            'nama_anggota'   => $transaksi->cicilan->pinjaman->user->name,
            'nominal_bayar'  => $transaksi->nominal_bayar,
            'tanggal_bayar'  => Carbon::parse($transaksi->tanggal_bayar)->translatedFormat('d F Y'),
            'metode_bayar'   => strtoupper($transaksi->metode_pembayaran),
            'bulan_ke'       => $transaksi->cicilan->bulan_ke,
            'pinjaman_id'    => $transaksi->cicilan->pinjaman_id
        ];

        $pdf = Pdf::loadView('pdf.kuitansi', $data);
        return $pdf->download('Kuitansi-' . $data['nomor_kuitansi'] . '.pdf');
    }
}
