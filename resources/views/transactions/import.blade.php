@extends('layout.sidebar')

@section('content')
  {{-- Judul Halaman (mengikuti struktur contoh) --}}
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 pt-3">Impor Data Transaksi</h1>
    
    {{-- Toolbar di kanan - Tombol kembali dihapus dari sini --}}
    <div class="btn-toolbar mb-2 mb-md-0">
      {{-- Dibiarkan kosong --}}
    </div>
  </div>

  {{-- Penanganan Pesan Error (dari file asli Anda) --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>Error!</strong> Terjadi masalah dengan input Anda.<br><br>
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Penanganan Pesan Error dari Sesi (dari file asli Anda) --}}
  @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif
  
  {{-- Penanganan Pesan Sukses (dari file contoh Anda) --}}
  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  {{-- Konten Form di dalam Card agar terlihat rapi --}}
  <div class="card">
    <div class="card-header">
      Formulir Unggah Excel
    </div>
    <div class="card-body">
      <p class="card-text">Silakan upload file Excel (.xlsx, .xls) dengan format yang sesuai.</p>
      
      <form action="{{ route('transactions.import.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        <div class="mb-3">
          <label for="file" class="form-label">Pilih File Excel:</label>
          {{-- Menggunakan kelas Bootstrap 'form-control' --}}
          <input type="file" name="file" id="file" class="form-control" required>
        </div>
        
        {{-- Wrapper untuk memindahkan tombol ke kanan --}}
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-file-earmark-arrow-up me-1"></i>
            Impor Data
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Tombol Kembali (dipindahkan ke bawah card) --}}
  <div class="mt-3">
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i>
      Kembali ke Daftar Transaksi
    </a>
  </div>

@endsection
