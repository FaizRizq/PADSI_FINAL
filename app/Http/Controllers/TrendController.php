<?php

namespace App\Http\Controllers;

use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 

class TrendController extends Controller
{
    public function index(Request $request): View
    {
        // === 1. MENGAMBIL DATA UNTUK FILTER DROPDOWN ===
        $availableMonths = Transaction::query()
            ->select(DB::raw("DATE_FORMAT(waktu_transaksi, '%Y-%m') as bulan_tahun"))
            ->groupBy('bulan_tahun')
            ->orderBy('bulan_tahun', 'desc') 
            ->get();

        // === 2. MENENTUKAN FILTER YANG SEDANG AKTIF ===
        $selectedMonth = $request->input(
            'filter_bulan',
            Carbon::now()->format('Y-m') 
        );

        $selectedYear = Carbon::parse($selectedMonth)->year;
        $selectedMonthOnly = Carbon::parse($selectedMonth)->month;

        // === 3. MENGAMBIL DATA KPI (KOTAK STATISTIK) ===
        $kpiData = Transaction::query()
            ->whereYear('waktu_transaksi', $selectedYear)
            ->whereMonth('waktu_transaksi', $selectedMonthOnly)
            ->select(
                DB::raw('COUNT(id) as total_penjualan'), 
                DB::raw('SUM(total) as total_transaksi_rupiah') 
            )
            ->first(); 

        // === 4. MENGAMBIL DATA UNTUK CHART (DIPERBARUI DI SINI) ===
        // Ambil data Frekuensi DAN Pendapatan per bulan sekaligus
        $chartRawData = Transaction::query()
            ->select(
                DB::raw("DATE_FORMAT(waktu_transaksi, '%Y-%m') as bulan"),
                DB::raw('COUNT(id) as total_penjualan_per_bulan'), // Data Grafik 1
                DB::raw('SUM(total) as total_pendapatan_per_bulan') // <--- TAMBAHAN UNTUK GRAFIK 2 (Rupiah)
            )
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc') 
            ->get();

        // Siapkan data untuk dikirim ke Chart.js
        $chartLabels = $chartRawData->pluck('bulan'); 
        $chartValues = $chartRawData->pluck('total_penjualan_per_bulan'); // Data Grafik 1 (Biru)
        
        // <--- TAMBAHAN UNTUK GRAFIK 2 --->
        $chartRevenueValues = $chartRawData->pluck('total_pendapatan_per_bulan'); // Data Grafik 2 (Hijau/Rupiah)

        // === 5. KIRIM SEMUA DATA KE VIEW ===
        return view('trends.index', [
            'kpiData' => $kpiData,
            'availableMonths' => $availableMonths,
            'selectedMonth' => $selectedMonth,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'chartRevenueValues' => $chartRevenueValues, // <--- JANGAN LUPA DITAMBAHKAN KE SINI
        ]);
    }
}