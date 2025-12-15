@extends('layout.sidebar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Diskon</h4>
        <a href="{{ route('diskon.create') }}" class="btn btn-danger">+ Tambah Diskon</a>
    </div>

    <form method="GET" action="{{ route('diskon.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari diskon..." class="form-control me-2" style="max-width:300px;">
        <button type="submit" class="btn btn-danger">Cari</button>
    </form>

    <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>ID Diskon</th>
                <th>Nama Diskon</th>
                <th>Jumlah Potongan (Rp)</th>
                <th>Masa Aktif</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($diskon as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong class="text-primary">{{ $d->id_diskon }}</strong></td> 
                <td>{{ $d->nama_diskon }}</td>
                <td>{{ number_format($d->jumlah_potongan, 0, ',', '.') }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($d->tanggal_mulai)->format('d/m/Y') }}
                    –
                    {{ \Carbon\Carbon::parse($d->tanggal_selesai)->format('d/m/Y') }}
                </td>
                <td>
                    <a href="{{ route('diskon.edit', ['id' => $d->id_diskon]) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('diskon.destroy', $d->id_diskon) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus diskon ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data diskon</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection