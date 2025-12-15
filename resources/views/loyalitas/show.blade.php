@extends('layout.sidebar')

@section('content')
<div class="container mt-4">

    {{-- ====== DETAIL PELANGGAN ====== --}}
    <h2 class="mb-3">Detail Pelanggan</h2>

    <div class="card shadow-sm border-0 p-4 mb-4">
        <div class="row mb-2">
            <div class="col-md-6">
                <p><strong>Nama:</strong> {{ $pelanggan->Nama_Pelanggan }}</p>
                <p><strong>No. Telepon:</strong> {{ $pelanggan->NoTelp_Pelanggan }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>ID Loyalitas:</strong> {{ $pelanggan->ID_Loyalitas }}</p>
                <p><strong>Dibuat:</strong> {{ $pelanggan->created_at ? $pelanggan->created_at->format('d M Y H:i') : '-' }}</p>
            </div>
        </div>

        @php
        // Hitung total transaksi menggunakan relasi yang benar
        $jumlahTransaksi = $pelanggan->transactions->count();
        // Gunakan kolom 'total' sesuai database baru kamu, bukan 'harga'
        $totalPembayaran = $pelanggan->transactions->sum('total');
        @endphp

        <div class="d-flex gap-4">
            <p class="mt-2"><strong>Total Transaksi:</strong> {{ $jumlahTransaksi }}x</p>
            <p class="mt-2"><strong>Total Belanja:</strong> Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- ====== TABEL TRANSAKSI (UPDATED) ====== --}}
        <div class="col-md-12">
            <div class="card shadow-sm border-0 p-3">
                <h4 class="mb-3">Riwayat Transaksi</h4>

                @if($pelanggan->transactions->isEmpty())
                <div class="alert alert-info mb-0">Belum ada transaksi untuk pelanggan ini.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0 table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>No. Nota</th>
                                <th>Waktu Transaksi</th>
                                <th>Diskon</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggan->transactions as $trx)
                            <tr>
                                <td>{{ $trx->id }}</td>
                                <td>{{ $trx->nomor_nota }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->translatedFormat('d M Y H:i') }}
                                </td>
                                <td class="text-danger">
                                    {{-- Tampilkan 0 jika null --}}
                                    Rp {{ number_format($trx->diskon ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- ====== TABEL DISKON ====== --}}
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 fw-bold text-dark">
                        <i class="fas fa-ticket-alt me-1"></i> Diskon yang Dapat Digunakan
                    </h6>
                </div>
                <div class="card-body">
                    @if($diskonTersedia->isEmpty())
                    <p class="text-muted text-center my-3">Tidak ada diskon tersedia saat ini.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Diskon</th>
                                    <th>Potongan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($diskonTersedia as $d)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $d->nama_diskon }}</td>
                                    <td class="text-success fw-bold">
                                        Rp {{ number_format($d->jumlah_potongan, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="small text-muted mb-1">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($d->tanggal_mulai)->format('d M Y') }}
                                            s/d
                                            {{ \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') }}
                                        </div>

                                        {{-- Syarat Min. Transaksi --}}
                                        @if($d->minimal_transaksi > 0)
                                        <span class="badge bg-warning text-dark">
                                            Min. {{ $d->minimal_transaksi }}x Transaksi
                                        </span>
                                        @endif

                                        {{-- Syarat Min. Pembayaran --}}
                                        @if($d->minimal_pembayaran > 0)
                                        <span class="badge bg-info text-dark">
                                            Min. Belanja Rp {{ number_format($d->minimal_pembayaran, 0, ',', '.') }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ====== FORM GUNAKAN DISKON ====== --}}
    <div class="card shadow-sm border-0 p-4 mt-4">
        <h5 class="mb-3">Gunakan Diskon</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="diskon_id" class="form-label">Pilih Diskon</label>
                {{-- Form Action menggunakan ID_Loyalitas --}}
                <form action="{{ route('idDiskonTerpakai', ['id' => $pelanggan->ID_Loyalitas]) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <select name="diskon_id" class="form-select">
                            @foreach($diskonTersedia as $diskon)
                            <option value="{{ $diskon->id_diskon }}">{{ $diskon->nama_diskon }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary text-nowrap">Gunakan</button>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tanggal Penggunaan</label>
                <input type="text" value="{{ now()->format('Y-m-d') }}" class="form-control" readonly>
            </div>
        </div>
    </div>

    {{-- ====== TOMBOL AKSI ====== --}}
    <div class="d-flex justify-content-end gap-2 mt-4 mb-5">
        {{-- Tombol Edit menggunakan ID_Pelanggan (Primary Key) --}}
        <a href="{{ route('loyalitas.edit', $pelanggan->ID_Pelanggan) }}" class="btn btn-warning">Edit</a>

        <a href="{{ route('loyalitas.index') }}" class="btn btn-secondary">Kembali</a>

        {{-- Tombol Hapus menggunakan ID_Pelanggan --}}
        <form action="{{ route('loyalitas.destroy', $pelanggan->ID_Pelanggan) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
    </div>

</div>
@endsection