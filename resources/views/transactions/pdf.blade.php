<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #BF092F; }
        .header p { margin: 5px 0; color: #555; }
        
        /* Style Tabel Utama */
        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .main-table th, .main-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .main-table th { background-color: #f2f2f2; font-weight: bold; }
        
        /* Helper Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-white { color: #ffffff; }
        
        /* Style Ringkasan (Summary Box) */
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 5px; margin-bottom: 20px; }
        .card { padding: 10px; border-radius: 5px; vertical-align: top; }
        .card-title { font-size: 14px; font-weight: bold; margin: 0 0 5px 0; }
        .card-text { font-size: 12px; margin: 0; }
        
        /* Warna Background Sesuai Brand */
        .bg-shopee { background-color: #EE4D2D; }
        .bg-grab { background-color: #00B14F; }
        .bg-go { background-color: #00AA13; }

        .footer-summary { float: right; width: 40%; margin-top: 10px; }
        .footer-summary table { width: 100%; border: none; }
    </style>
</head>
<body>

    <div class="header">
        <h2>DINGDONG KIMBAB & RAMYUN</h2>
        <p>Laporan Transaksi Periode: {{ $periode }}</p>
    </div>

    {{-- TABEL RINGKASAN KPI (KOTAK WARNA-WARNI) --}}
    <table class="summary-table">
        <tr>
            {{-- Kotak ShopeeFood --}}
            <td class="card bg-shopee text-white" width="33%">
                <h3 class="card-title">ShopeeFood</h3>
                <p class="card-text">
                    {{ $shopeeStats->jumlah_transaksi }} Transaksi<br>
                    Total: <strong>Rp {{ number_format($shopeeStats->total_rupiah, 0, ',', '.') }}</strong>
                </p>
            </td>

            {{-- Kotak GrabFood --}}
            <td class="card bg-grab text-white" width="33%">
                <h3 class="card-title">GrabFood</h3>
                <p class="card-text">
                    {{ $grabfoodStats->jumlah_transaksi }} Transaksi<br>
                    Total: <strong>Rp {{ number_format($grabfoodStats->total_rupiah, 0, ',', '.') }}</strong>
                </p>
            </td>

            {{-- Kotak GoFood --}}
            <td class="card bg-go text-white" width="33%">
                <h3 class="card-title">GoFood</h3>
                <p class="card-text">
                    {{ $gofoodStats->jumlah_transaksi }} Transaksi<br>
                    Total: <strong>Rp {{ number_format($gofoodStats->total_rupiah, 0, ',', '.') }}</strong>
                </p>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA TRANSAKSI --}}
    <table class="main-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Pelanggan</th>
                <th>Tipe Bayar</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->waktu_transaksi)->format('d/m/Y H:i') }}</td>
                <td>{{ $t->nomor_nota }}</td>
                <td>{{ $t->pelanggan }}</td>
                <td>{{ $t->tipe_bayar }}</td>
                <td class="text-right">{{ number_format($t->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total Pemasukan Periode Ini</th>
                <th class="text-right">{{ number_format($totalPemasukan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer-summary">
        <table>
            <tr>
                <td><strong>Total Transaksi:</strong></td>
                <td class="text-right">{{ $totalTransaksi }} Transaksi</td>
            </tr>
            <tr>
                <td><strong>Dicetak Pada:</strong></td>
                <td class="text-right">{{ date('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

</body>
</html>