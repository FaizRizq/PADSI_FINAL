@extends('layout.sidebar')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4 fw-bold">Ubah Diskon</h4>

            <form action="{{ route('diskon.update', $diskon->id_diskon) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ID Diskon --}}
                <div class="mb-3">
                    <label for="id_diskon" class="form-label">ID Diskon</label>
                    <input type="text" id="id_diskon" class="form-control"
                        value="{{ old('id_diskon', $diskon->id_diskon ?? '') }}"
                        maxlength="10" readonly>
                    
                    <input type="hidden" name="id_diskon" value="{{ $diskon->id_diskon }}">
                </div>

                <div class="mb-3">
                    <label for="nama_diskon" class="form-label">Nama Diskon</label>
                    <input type="text" name="nama_diskon" id="nama_diskon"
                        value="{{ old('nama_diskon', $diskon->nama_diskon) }}"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="jumlah_potongan" class="form-label">Jumlah Potongan (Rp)</label>
                    <input type="number" name="jumlah_potongan" id="jumlah_potongan"
                        value="{{ old('jumlah_potongan', $diskon->jumlah_potongan) }}"
                        class="form-control" min="0" required>
                </div>

                <div class="row g-2 mb-3">
    <div class="col-md-6">
        <label for="minimal_pembayaran" class="form-label">Syarat Minimal Pembayaran (Rp)</label>
        <input id="minimal_pembayaran" name="minimal_pembayaran" type="text" class="form-control"
               value="{{ old('minimal_pembayaran', $diskon->minimal_pembayaran) }}" placeholder="0">
        @error('minimal_pembayaran')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="minimal_transaksi" class="form-label">Minimal Transaksi (kali)</label>
        <input id="minimal_transaksi" name="minimal_transaksi" type="number" min="0" class="form-control"
               value="{{ old('minimal_transaksi', $diskon->minimal_transaksi) }}" placeholder="0">
        @error('minimal_transaksi')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Masa Aktif</label>
                    <div class="d-flex align-items-center">
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $diskon->tanggal_mulai) }}"
                            class="form-control me-2" required>
                        <span class="mx-2">hingga</span>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $diskon->tanggal_selesai) }}"
                            class="form-control" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('diskon.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection