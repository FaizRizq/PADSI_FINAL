@extends('layout.sidebar')

@section('content')
<div class="container mt-4">
    <h2>Tambah Pelanggan</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('loyalitas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Nama_Pelanggan" class="form-label">Nama Pelanggan</label>
            <input type="text" name="Nama_Pelanggan" class="form-control" placeholder="Masukkan Nama Pelanggan" required>
        </div>
        <div class="mb-3">
            <label for="NoTelp_Pelanggan" class="form-label">Nomor Telepon</label>
            <input type="text" name="NoTelp_Pelanggan" class="form-control" placeholder="Masukkan nomor telepon" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('loyalitas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection