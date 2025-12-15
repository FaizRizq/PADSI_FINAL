@extends('layout.sidebar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Ekspor Laporan Transaksi</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Filter Laporan PDF</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.export.submit') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        {{-- Kolom Pilih Bulan --}}
                        <div class="col-md-6">
                            <label for="bulan" class="form-label fw-bold">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Bulan --</option>
                                @foreach(range(1, 12) as $m)
                                    @php
                                        $namaBulan = \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F');
                                    @endphp
                                    <option value="{{ $m }}">{{ $namaBulan }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kolom Pilih Tahun --}}
                        <div class="col-md-6">
                            <label for="tahun" class="form-label fw-bold">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Tahun --</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                                
                                {{-- Fallback jika data kosong --}}
                                @if($years->isEmpty())
                                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info py-2">
                        <small><i class="bi bi-info-circle me-1"></i> Pastikan Anda memilih kombinasi Bulan dan Tahun yang memiliki data transaksi.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Unduh PDF
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection