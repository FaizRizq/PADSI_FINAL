@extends('layout.sidebar')

@section('content')
  {{-- 
    1. KEMBALIKAN d-flex justify-content-between 
       agar filter dan tombol bisa sejajar di kanan 
  --}}
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 pt-3">Analisis Laporan Transaksi</h1>
    
    {{-- 2. btn-toolbar akan menampung filter DAN tombol impor --}}
    <div class="btn-toolbar mb-2 mb-md-0">
      
      {{-- 3. FORM FILTER GABUNGAN (BULAN & TIPE BAYAR) --}}
      <form action="{{ route('transactions.index') }}" method="GET" class="d-flex me-3">
        
        {{-- A. Dropdown Bulan --}}
        <select name="filter_bulan" class="form-select form-select-sm me-2" style="width: 160px;">
            <option value="">Semua Bulan</option>
            @foreach($availableMonths as $data)
                @php
                    // Ubah format "2025-11" jadi "November 2025"
                    $label = \Carbon\Carbon::createFromFormat('Y-m', $data->bulan_tahun)->translatedFormat('F Y');
                @endphp
                <option value="{{ $data->bulan_tahun }}" {{ $selectedBulan == $data->bulan_tahun ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        {{-- B. Dropdown Tipe Bayar --}}
        <select name="tipe_bayar" class="form-select form-select-sm me-2" style="width: 160px;">
            <option value="">Semua Tipe Bayar</option>
            @foreach($tipesBayar as $tipe)
                <option value="{{ $tipe }}" {{ $selectedFilter == $tipe ? 'selected' : '' }}>
                    {{ $tipe }}
                </option>
            @endforeach
        </select>

        {{-- C. Tombol Submit Satu Saja --}}
        <button type="submit" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-funnel"></i> Filter
        </button>
      
      </form>
      {{-- AKHIR FORM FILTER --}}

      {{-- Tombol Impor (Tetap sama) --}}
      @if(Auth::user()->ID_Role !== 2)
      <div class="btn-group me-2">
        <a href="{{ route('transactions.import.form') }}" class="btn btn-sm btn-success">
          <i class="bi bi-file-earmark-arrow-up me-1"></i>
          Impor
        </a>
      </div>
      @endif

      {{-- Tombol Ekspor PDF (Manajer & Pemilik / Role 1 & 2) --}}
      @if(in_array(Auth::user()->ID_Role, [1, 2]))
            <a href="{{ route('transactions.export.form') }}" class="btn btn-sm btn-danger ms-1">
            <i class="bi bi-file-earmark-pdf me-1"></i>
            Ekspor
            </a>
      @endif
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

<div class="row mb-3">

  {{-- Kotak ShopeeFood --}}
  <div class="col-md-4 mb-3">
    <div class="card text-white" style="background-color: #EE4D2D;">
      <div class="card-header">ShopeeFood</div>
      <div class="card-body">
        <h5 class="card-title">{{ $shopeeStats->jumlah_transaksi }} Transaksi</h5>
        <p class="card-text">
          Total: <strong>Rp {{ number_format($shopeeStats->total_rupiah, 0, ',', '.') }}</strong>
        </p>
      </div>
    </div>
  </div>

  {{-- Kotak GrabFood --}}
  <div class="col-md-4 mb-3">
    <div class="card text-white" style="background-color: #00B14F;">
      <div class="card-header">GrabFood</div>
      <div class="card-body">
        <h5 class="card-title">{{ $grabfoodStats->jumlah_transaksi }} Transaksi</h5>
        <p class="card-text">
          Total: <strong>Rp {{ number_format($grabfoodStats->total_rupiah, 0, ',', '.') }}</strong>
        </p>
      </div>
    </div>
  </div>

  {{-- Kotak Gofood --}}
  <div class="col-md-4 mb-3">
    <div class="card text-white" style="background-color: #00AA13;">
      <div class="card-header">Gofood</div>
      <div class="card-body">
        <h5 class="card-title">{{ $gofoodStats->jumlah_transaksi }} Transaksi</h5>
        <p class="card-text">
          Total: <strong>Rp {{ number_format($gofoodStats->total_rupiah, 0, ',', '.') }}</strong>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="table-responsive">
  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <!-- <th scope="col">ID</th> -->
          <th scope="col">Waktu Transaksi</th>
          <th scope="col">Nomor Nota</th>
          <th scope="col">Pelanggan</th>
          <th scope="col">Diskon (Rp)</th> {{-- DITAMBAHKAN --}}
          <th scope="col">Pajak (Rp)</th> {{-- DITAMBAHKAN --}}
          <th scope="col">Total (Rp)</th>
          <th scope="col">Tipe Bayar</th>
        </tr>
      </thead>
      <tbody>

        @forelse ($transactions as $transaction)
        <tr>
          <!-- <td>{{ $transaction->id }}</td> -->
          <td>{{ $transaction->waktu_transaksi->format('d/m/Y H:i') }}</td>
          <td>{{ $transaction->nomor_nota }}</td>
          <td>{{ $transaction->pelanggan ?? '-' }}</td>
          <td>{{ number_format($transaction->diskon, 0, ',', '.') }}</td> {{-- DITAMBAHKAN --}}
          <td>{{ number_format($transaction->pajak, 0, ',', '.') }}</td> {{-- DITAMBAHKAN --}}
          <td>{{ number_format($transaction->total, 0, ',', '.') }}</td>
          <td>{{ $transaction->tipe_bayar }}</td>
        </tr>
        @empty
        <tr>
          {{-- Colspan diubah menjadi 8 --}}
          <td colspan="8" class="text-center">Tidak ada data transaksi, Silahkan masukkan data melalui tombol "Impor" dibagian kiri atas!.</td>
        </tr>
        @endforelse

      </tbody>
    </table>
  </div>
  @endsection

  @push('scripts')
  @endpush