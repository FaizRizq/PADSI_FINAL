@extends('layout.sidebar')

@section('title', 'Tambah Diskon')

@section('content')
<div class="card border-0 shadow-sm" style="max-width: 600px; margin:auto;">
    <div class="card-body">
        <h4 class="fw-semibold mb-3">Tambah Diskon</h4>
        <form action="{{ route('diskon.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_diskon" class="form-label">ID Diskon</label>
                <input type="text" name="id_diskon" id="id_diskon" class="form-control"
                    value="{{ 'Auto Generate' }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama_diskon" class="form-label">Nama Diskon</label>
                <input type="text" name="nama_diskon" id="nama_diskon" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jumlah_potongan" class="form-label">Jumlah Potongan (Rp)</label>
                <input type="number" name="jumlah_potongan" id="jumlah_potongan" class="form-control" required>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <label for="minimal_pembayaran" class="form-label">Syarat Minimal Pembayaran (Rp)</label>
                <input id="minimal_pembayaran" name="minimal_pembayaran" type="text" class="form-control"
                       value="{{ old('minimal_pembayaran', 0) }}" placeholder="0">
                @error('minimal_pembayaran')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6">
                <label for="minimal_transaksi" class="form-label">Minimal Transaksi (kali)</label>
                <input id="minimal_transaksi" name="minimal_transaksi" type="number" min="0" class="form-control"
                       value="{{ old('minimal_transaksi', 0) }}" placeholder="0">
                @error('minimal_transaksi')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
            </div>

<div class="mb-3">
    <label class="form-label">Masa Aktif Diskon</label>
    <div class="d-flex align-items-center gap-2">
        <input type="date" name="tanggal_mulai" class="form-control" required>
        <span class="fw-semibold">hingga</span>
        <input type="date" name="tanggal_selesai" class="form-control" required>
    </div>
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('diskon.index') }}" class="btn btn-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
</form>
</div>
</div>

<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
  const rupiah = el => el.replace(/\D/g, "");
  const formatRupiah = (num) => {
    if (!num) return '';
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  };

  const input = document.getElementById('minimal_pembayaran');
  const pot = document.getElementById('jumlah_potongan');

  [input, pot].forEach(i => {
    if (!i) return;
    i.addEventListener('input', function (e) {
      const raw = rupiah(this.value);
      this.value = raw ? formatRupiah(raw) : '';
    });
  });
});
</script> -->
@endsection