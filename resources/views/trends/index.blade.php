@extends('layout.sidebar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 pt-3">Tren Penjualan</h1>

    {{-- Filter Bulan (Sama seperti sebelumnya) --}}
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="{{ route('trends.index') }}" method="GET">
            <div class="input-group">
                <select class="form-select form-select-sm" name="filter_bulan">
                    @foreach ($availableMonths as $month)
                    <option value="{{ $month->bulan_tahun }}"
                        {{ $selectedMonth == $month->bulan_tahun ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($month->bulan_tahun . '-01')->format('F Y') }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

<h4 class="mb-3">
    Menampilkan data untuk: <strong>{{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}</strong>
</h4>

{{-- Bagian KPI (Sama seperti sebelumnya) --}}
<div class="row mb-3">
    <div class="col-md-6 mb-3">
        <div class="card text-dark bg-light">
            <div class="card-header">Total Penjualan (Bulan Ini)</div>
            <div class="card-body">
                <h5 class="card-title">{{ number_format($kpiData->total_penjualan ?? 0, 0, ',', '.') }} Transaksi</h5>
                <p class="card-text">Jumlah transaksi yang terjadi di bulan ini.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-header">Total Transaksi (Bulan Ini)</div>
            <div class="card-body">
                <h5 class="card-title">Rp {{ number_format($kpiData->total_transaksi_rupiah ?? 0, 0, ',', '.') }}</h5>
                <p class="card-text">Total pendapatan (Rupiah) yang diterima di bulan ini.</p>
            </div>
        </div>
    </div>
</div>

{{-- === GRAFIK 1: FREKUENSI (BIRU) === --}}
<div class="card mb-4">
    <div class="card-header">
        Grafik Frekuensi Penjualan (Jumlah Transaksi)
    </div>
    <div class="card-body">
        <canvas class="my-4 w-100" id="myTrendChart" width="900" height="380"></canvas>
    </div>
</div>

{{-- === GRAFIK 2: PENDAPATAN (HIJAU) - BARU DITAMBAHKAN === --}}
<div class="card mb-4">
    <div class="card-header text-success">
        <strong>Grafik Total Pendapatan (Rupiah)</strong>
    </div>
    <div class="card-body">
        {{-- ID Canvas harus beda, di sini kita pakai 'myRevenueChart' --}}
        <canvas class="my-4 w-100" id="myRevenueChart" width="900" height="380"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- DATA DARI CONTROLLER ---
    const chartLabels = @json($chartLabels);
    const chartValues = @json($chartValues); // Data Jumlah Transaksi
    
    // Pastikan variable ini dikirim dari controller (lihat Langkah 1)
    // Gunakan operator null coalescing (?? []) agar tidak error jika data kosong
    const chartRevenueValues = @json($chartRevenueValues ?? []); 


    // --- CONFIG GRAFIK 1 (BIRU - FREKUENSI) ---
    const ctx1 = document.getElementById('myTrendChart');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: chartValues,
                lineTension: 0.1,
                backgroundColor: 'rgba(0, 123, 255, 0.1)', 
                borderColor: 'rgba(0, 123, 255, 1)', 
                borderWidth: 4,
                pointBackgroundColor: 'rgba(0, 123, 255, 1)'
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });

    // --- CONFIG GRAFIK 2 (HIJAU - PENDAPATAN) ---
    const ctx2 = document.getElementById('myRevenueChart');
    new Chart(ctx2, {
        type: 'line', // Bisa diganti 'bar' jika ingin bentuk batang
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: chartRevenueValues, // Data Rupiah
                lineTension: 0.1,
                // Warna Hijau (Success)
                backgroundColor: 'rgba(25, 135, 84, 0.1)', 
                borderColor: 'rgba(25, 135, 84, 1)', 
                borderWidth: 4,
                pointBackgroundColor: 'rgba(25, 135, 84, 1)'
            }]
        },
        options: {
            scales: { 
                y: { 
                    beginAtZero: true,
                    ticks: {
                        // Format angka di sumbu Y agar tampil ada 'Rp' atau ribuan
                        callback: function(value, index, values) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                } 
            },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        // Format tooltip saat mouse hover agar ada 'Rp'
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush