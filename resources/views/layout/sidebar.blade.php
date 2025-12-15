<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Mengambil Nama Aplikasi dari .env --}}
  <title>{{ config('app.name', 'SIALDONG / SIPLP') }}</title>

  {{-- --- TAMBAHKAN BAGIAN INI UNTUK LOGO --- --}}
  <link rel="shortcut icon" href="{{ asset('img/dingdong_logo.jpg') }}" type="image/x-icon">
  {{-- --------------------------------------- --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>

<body>

  <header class="navbar navbar-light flex-md-nowrap p-0 shadow" style="background-color: #BF092F;">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white fw-bold" href="#">SIALDONG / SIPLP</a>

    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>


    <div class="nav-item">

      <span class="nav-link px-3 text-white">
        {{-- Tampilkan nama pengguna yang login --}}
        Selamat datang, <strong>{{ Auth::user()->Nama_Pengguna ?? 'Pengguna' }}</strong>
      </span>

    </div>

  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background-color: #BF092F;">

        {{-- position-sticky akan menampung 2 grup: link navigasi & tombol logout --}}
        <div class="position-sticky pt-3">

          {{-- 1. Grup Link Navigasi --}}
          {{-- 'mb-auto' akan mendorong grup ini (dan grup di bawahnya) --}}
          <ul class="nav nav-pills flex-column mb-auto">

            {{-- Dashboard (Asli) --}}
            {{-- Cek: Jika Role ID BUKAN 3 (Kasir), maka tampilkan menu ini --}}
            @if(Auth::user()->ID_Role !== 3)
            <li class="nav-item mb-3">
              <a href="{{ route('transactions.index') }}" class="nav-link fs-6 {{ request()->routeIs('transactions.index') ? 'active' : '' }} " aria-current="page">
                <i class="bi bi-house-door me-2"></i>
                Dashboard
              </a>
            </li>

            {{-- Kelola Diskon --}}
            <li class="nav-item mb-3">
              <a href="{{ route('diskon.index') }}"
                class="nav-link fs-6 {{ request()->routeIs('diskon.index') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i>
                Kelola Diskon
              </a>
            </li>
            @endif


            {{-- Tren (Menambahkan mb-3 dan style latar belakang) --}}
            <li class="nav-item mb-3">
              <a href="{{ route('trends.index') }}" class="nav-link fs-6 {{ request()->routeIs('trends.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text me-2"></i>
                Tren
              </a>
            </li>

            {{-- Loyalitas (Menambahkan mb-3 dan style latar belakang) --}}
            <li class="nav-item mb-3">
              <a href="{{ route('loyalitas.index') }}" class="nav-link fs-6 {{ request()->routeIs('loyalitas.index') ? 'active' : '' }}">
                <i class="bi bi-cart me-2"></i>
                Loyalitas
              </a>
            </li>

            {{-- Item Logout dipindahkan dari sini --}}

            {{-- ... Item menu lainnya ... --}}
          </ul>



          {{-- 2. Grup Tombol Logout --}}
          {{-- Grup ini akan terdorong ke bawah oleh 'mb-auto' dari grup di atas --}}
          <ul class="nav nav-pills flex-column">
            <li class="nav-item mb-3">
              <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="nav-link fs-6" style="width: 100%; text-align: left;">
                  <i class="bi bi-box-arrow-right me-2"></i>
                  Logout
                </button>
              </form>
            </li>
          </ul>

        </div>
      </nav>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        @yield('content')

      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  @stack('scripts')
</body>

</html>