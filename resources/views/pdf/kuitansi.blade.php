<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi Pembayaran Koperasi</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 16px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; font-weight: bold; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #f0f0f0; border-bottom: 1px solid #ddd; font-weight: bold; padding: 10px; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; padding: 10px; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #333; font-weight: bold; padding: 10px; }
        .terbilang { font-style: italic; margin-top: 20px; font-size: 14px; color: #555; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title" style="font-size: 24px; color: #2c3e50;">Koperasi Tunas Arta Mandiri</td>
                            <td>
                                Nomer Kuitansi: {{ $nomor_kuitansi }}<br>
                                Tanggal: {{ $tanggal_bayar }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                JL. Kapten P. Tendean, Demangan,<br>
                                Kota Madiun, Jawa Timur
                            </td>
                            <td>
                                **Diberikan Kepada:**<br>
                                {{ $nama_anggota }}<br>
                                Status: Anggota Aktif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Deskripsi Pembayaran</td>
                <td>Total</td>
            </tr>

            <tr class="item">
                <td>Pembayaran Cicilan Pinjaman (ID Pinjaman: {{ $pinjaman_id }}) - Bulan Ke-{{ $bulan_ke }}</td>
                <td>Rp {{ number_format($nominal_bayar, 2, ',', '.') }}</td>
            </tr>

            <tr class="heading">
                <td>Metode Pembayaran</td>
                <td>Status</td>
            </tr>
            <tr class="details">
                <td>{{ $metode_bayar }}</td>
                <td style="color: green; font-weight: bold;">LUNAS</td>
            </tr>

            <tr class="total">
                <td></td>
                <td>Total Bayar: Rp {{ number_format($nominal_bayar, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
