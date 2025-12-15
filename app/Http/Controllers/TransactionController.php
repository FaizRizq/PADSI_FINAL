<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request; // <-- DIPERLUKAN UNTUK FILTER
use Illuminate\View\View;
use App\Imports\TransactionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi dan statistik.
     */
    public function index(Request $request): View
    {
        // === 1. AMBIL DATA UNTUK DROPDOWN FILTER ===
        
        // A. Daftar Tipe Bayar (Sama seperti sebelumnya)
        $tipesBayar = Transaction::select('tipe_bayar')->distinct()->pluck('tipe_bayar');

        // B. Daftar Bulan Tersedia (Logika dari TrendController)
        $availableMonths = Transaction::query()
            ->select(DB::raw("DATE_FORMAT(waktu_transaksi, '%Y-%m') as bulan_tahun"))
            ->groupBy('bulan_tahun')
            ->orderBy('bulan_tahun', 'desc') // Bulan terbaru paling atas
            ->get();

        // === 2. TANGKAP INPUT FILTER DARI USER ===
        $selectedTipe = $request->input('tipe_bayar');
        $selectedBulan = $request->input('filter_bulan'); // Input baru untuk bulan

        // === 3. MULAI QUERY ===
        $query = Transaction::query();

        // Filter A: Berdasarkan Tipe Bayar
        if ($selectedTipe) {
            $query->where('tipe_bayar', $selectedTipe);
        }

        // Filter B: Berdasarkan Bulan (Y-m)
        if ($selectedBulan) {
            // $selectedBulan formatnya "2025-11"
            $date = Carbon::createFromFormat('Y-m', $selectedBulan);
            $query->whereYear('waktu_transaksi', $date->year)
                  ->whereMonth('waktu_transaksi', $date->month);
        }

        // Urutkan data
        $query->orderBy('waktu_transaksi', 'desc');

        // Ambil data (Gunakan paginate agar halaman tidak berat jika data ribuan)
        $transactions = $query->get(); 

        // === 4. HITUNG STATISTIK (KPI) ===
        // Statistik ini tetap GLOBAL (tidak ikut filter) atau mau ikut filter?
        // Biasanya KPI dashboard mengikuti filter agar relevan. 
        // Di sini saya buat STATISTIK MENGIKUTI FILTER agar dinamis.
        
        // Kita buat query terpisah untuk stats agar tidak terganggu pagination
        $statsQuery = Transaction::query();
        if ($selectedTipe) $statsQuery->where('tipe_bayar', $selectedTipe);
        if ($selectedBulan) {
            $d = Carbon::createFromFormat('Y-m', $selectedBulan);
            $statsQuery->whereYear('waktu_transaksi', $d->year)->whereMonth('waktu_transaksi', $d->month);
        }

        $stats = $statsQuery->select(
                'tipe_bayar', 
                DB::raw('count(*) as jumlah_transaksi'),
                DB::raw('sum(total) as total_rupiah')
            )
            ->whereIn('tipe_bayar', ['ShopeeFood', 'GoFood', 'GrabFood'])
            ->groupBy('tipe_bayar')
            ->get()
            ->keyBy('tipe_bayar');

        $defaultStats = (object)['jumlah_transaksi' => 0, 'total_rupiah' => 0];
        $shopeeStats = $stats->get('ShopeeFood', $defaultStats);
        $gofoodStats = $stats->get('GoFood', $defaultStats);
        $grabfoodStats = $stats->get('GrabFood', $defaultStats);
    
        // === 5. KIRIM KE VIEW ===
        return view('transactions.index', [
            'transactions'    => $transactions,
            'shopeeStats'     => $shopeeStats,
            'gofoodStats'     => $gofoodStats,
            'grabfoodStats'   => $grabfoodStats,
            'tipesBayar'      => $tipesBayar,
            'availableMonths' => $availableMonths, // <-- Data dropdown bulan
            'selectedFilter'  => $selectedTipe,
            'selectedBulan'   => $selectedBulan,   // <-- Bulan yang sedang dipilih
        ]);
    }

    // ... (method showImportForm() dan import() Anda tetap sama)

    public function showImportForm(): View
    {
        return view('transactions.import');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $file = $request->file('file');

        try {
            Excel::import(new TransactionImport, $file);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data. Pastikan format file Anda benar. Error: ' . $e->getMessage());
        }

        return redirect()->route('transactions.index')
                         ->with('success', 'Data transaksi berhasil diimpor!');
    }

  

     public function showExportForm()
    {
        // Ambil daftar Tahun unik yang ada datanya
        $years = Transaction::selectRaw('YEAR(waktu_transaksi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('transactions.export', compact('years'));
    }

    /**
     * Memproses Filter dan Menghasilkan PDF
     */
    public function exportPDF(Request $request)
    {
        // 1. Validasi Input (Sekarang Tahun dan Bulan terpisah)
        $request->validate([
            'tahun' => 'required|numeric',
            'bulan' => 'required|numeric|min:1|max:12',
        ]);

        $year = $request->input('tahun');
        $month = $request->input('bulan');

        // Buat objek Carbon untuk label periode (misal: "November 2025")
        $date = Carbon::createFromDate($year, $month, 1);
        $periodeLabel = $date->translatedFormat('F Y');

        // 2. Query Data
        $query = Transaction::query();
        $query->whereYear('waktu_transaksi', $year);
        $query->whereMonth('waktu_transaksi', $month);
        
        // Ambil data transaksi
        $transactions = $query->orderBy('waktu_transaksi', 'asc')->get();

        // --- LOGIKA STATISTIK (KPI) ---
        // (Sama persis dengan logika yang Anda kirimkan)
        
        $hitungStats = function($tipe) use ($transactions) {
            $filtered = $transactions->where('tipe_bayar', $tipe);
            return (object) [
                'jumlah_transaksi' => $filtered->count(),
                'total_rupiah' => $filtered->sum('total')
            ];
        };

        $shopeeStats = $hitungStats('ShopeeFood');
        $grabfoodStats = $hitungStats('GrabFood');
        $gofoodStats = $hitungStats('GoFood');

        // Hitung Total Keseluruhan
        $totalPemasukan = $transactions->sum('total');
        $totalTransaksi = $transactions->count();

        // 3. Generate PDF
        $pdf = Pdf::loadView('transactions.pdf', [
            'transactions' => $transactions,
            'periode' => $periodeLabel,
            'totalPemasukan' => $totalPemasukan,
            'totalTransaksi' => $totalTransaksi,
            // Kirim data statistik ke View PDF
            'shopeeStats' => $shopeeStats,
            'grabfoodStats' => $grabfoodStats,
            'gofoodStats' => $gofoodStats,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan_Transaksi_' . str_replace(' ', '_', $periodeLabel) . '.pdf';
        
        return $pdf->download($filename);
    }
}