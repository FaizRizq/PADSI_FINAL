{{-- Ini bisa meng-extend layout utama Anda, misal @extends('layout.sidebar') --}}
@extends('layout.sidebar')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Impor Data Transaksi</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Upload file Excel (XLSX, XLS) atau CSV yang berisi data transaksi.
                        Pastikan nama header kolom di file Anda sudah benar.
                    </p>

                    {{-- Tampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tampilkan pesan error --}}
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Tampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Upload File --}}
                    <form action="{{ route('transaksi.handleImport') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file_transaksi" class="form-label">Pilih File Transaksi</label>
                            <input class="form-control" type="file" id="file_transaksi" name="file_transaksi" required>
                            <div class="form-text">
                                Hanya menerima file .xlsx, .xls, atau .csv
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i>
                            Import
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- 1. Jam Sibuk Hari Ini
SELECT 
    HOUR(waktu_transaksi) AS jam, 
    COUNT(*) AS jumlah_transaksi
FROM 
    transactions
GROUP BY 
    HOUR(waktu_transaksi)
ORDER BY 
    jumlah_transaksi DESC; -->

<!-- 2. Bulan ini vs Bulan Lalu
SELECT 
    total_ini,
    total_lalu,
    ROUND(
        ((total_ini - total_lalu) / NULLIF(total_lalu, 0)) * 100, 
    2) AS persentase_pertumbuhan
FROM (
    SELECT 
        SUM(CASE 
            WHEN DATE_FORMAT(waktu_transaksi, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m') 
            THEN total ELSE 0 END) AS total_ini,
            
        SUM(CASE 
            WHEN DATE_FORMAT(waktu_transaksi, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m') 
            THEN total ELSE 0 END) AS total_lalu
    FROM 
        transactions
) AS data_sementara; -->