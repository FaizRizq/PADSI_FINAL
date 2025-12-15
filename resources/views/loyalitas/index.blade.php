@extends('layout.sidebar')

@section('content')
<div class="container mt-4">
    {{-- Header dan Search sejajar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Loyalitas Pelanggan</h2>

        {{-- Input Search Realtime --}}
        <input type="text" id="search" class="form-control w-25" placeholder="Cari pelanggan...">
    </div>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    

    <div class="d-flex align-items-center gap-2">
    
    <!-- Tombol Tambah Pelanggan -->
    <a href="{{ route('loyalitas.create') }}" class="btn btn-primary">
        + Tambah Pelanggan
    </a>


</div>

{{-- Tabel Pelanggan --}}
<table class="table table-bordered" id="pelanggan-table">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>No Telepon</th>
            <th>ID Loyalitas</th>
            <th>Transaksi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loyalitas as $index => $l)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $l->pelanggan ? $l->pelanggan->Nama_Pelanggan : $l->Nama_Pelanggan }}</td>
    <td>{{ $l->pelanggan ? $l->pelanggan->NoTelp_Pelanggan : $l->NoTelp_Pelanggan }}</td>
    <td>{{ $l->ID_Loyalitas }}</td>
    <td>{{ $l->Jumlah_Transaksi }}</td>
    <td>
        <a href="{{ route('loyalitas.show', $l->ID_Loyalitas) }}" class="btn btn-info btn-sm">Detail</a>
    </td>
</tr>
@endforeach

    </tbody>
</table>
</div>

{{-- Script untuk realtime search --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var query = $(this).val();
            $.ajax({
                url: "{{ route('loyalitas.search') }}",
                type: "GET",
                data: {
                    'q': query
                },
                success: function(data) {
                    $('#pelanggan-table tbody').html(data);
                }
            });
        });
    });
</script>
@endsection