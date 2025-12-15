@extends('layout.sidebar')

@section('content')
<div class="container mt-4">
    <h2>Edit Pelanggan</h2>

    {{-- Pesan Error --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('loyalitas.update', $pelanggan->ID_Pelanggan) }}" method="POST">
        @csrf
        @method('PUT') {{-- <--- WAJIB ADA UNTUK UPDATE --}}

        {{-- Input form kamu di sini --}}
        <div class="mb-3">
            <label>Nama Pelanggan</label>
            <input type="text" name="Nama_Pelanggan" value="{{ $pelanggan->Nama_Pelanggan }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="NoTelp_Pelanggan" class="form-label">No. Telepon</label>
            <input type="text" name="NoTelp_Pelanggan" id="NoTelp_Pelanggan" class="form-control"
                value="{{ old('NoTelp_Pelanggan', $pelanggan->NoTelp_Pelanggan) }}"
                required maxlength="12" minlength="12"
                pattern="\d{12}" title="Nomor telepon harus 12 digit angka.">
            <div id="telp-warning" class="text-danger small mt-1" style="display:none;">
                Nomor telepon harus tepat 12 digit angka.
            </div>
        </div>

        <div class="mb-3">
            <label for="ID_Loyalitas" class="form-label">ID Loyalitas</label>
            <input type="text" id="ID_Loyalitas" class="form-control"
                value="{{ $pelanggan->ID_Loyalitas }}" readonly>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('loyalitas.show', $pelanggan->ID_Loyalitas) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
</div>
</form>

{{-- Validasi realtime no_telp --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const telpInput = document.getElementById('no_telp');
        const warning = document.getElementById('telp-warning');

        telpInput.addEventListener('input', function() {
            const value = telpInput.value;
            const isValid = /^\d{12}$/.test(value);

            if (!isValid && value.length > 0) {
                warning.style.display = 'block';
            } else {
                warning.style.display = 'none';
            }
        });
    });
</script>
@endsection