<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  {{-- Mengambil Nama Aplikasi dari .env --}}
  <title>LOGIN SIALDONG</title>

  {{-- --- TAMBAHKAN BAGIAN INI UNTUK LOGO --- --}}
  <link rel="shortcut icon" href="{{ asset('img/dingdong_logo.jpg') }}" type="image/x-icon">
  {{-- --------------------------------------- --}}
    @vite('resources/css/app.css')
</head>

<body class="w-full h-full bg-white">
    <div class="flex flex-col lg:flex-row min-h-screen">

        <div class="hidden lg:flex lg:w-1/2 items-center justify-center bg-white p-12">
            <img src="{{ asset('img/dingdong_logo.jpg') }}" alt="SIALDONG" width="500" height="500">
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-red-800 px-4 py-12 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white">
                        Masuk ke akun Anda
                    </h2>
                    <p class="mt-2 text-sm text-white">
                        Selamat datang kembali!
                    </p>
                </div>

                <div class="mt-8">
                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-white">
                                Email
                            </label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" required
                                    class="block w-full rounded-md border-0 bg-white py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6
                                    @error('email') ring-red-500 @enderror"
                                    placeholder="anda@email.com" value="{{ old('email') }}">

                                @error('email')
                                <p class="mt-1 text-sm text-yellow-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-medium leading-6 text-white">
                                    Password
                                </label>
                            </div>
                            <div class="mt-2 relative">
                                <input id="password" name="password" type="password" required
                                    class="block w-full rounded-md border-0 bg-white py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                                    placeholder="••••••••">

                                {{-- Tombol Mata (Ikon) --}}
                            </div>

                            @error('password')
                            <p class="mt-1 text-sm text-yellow-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-white px-3 py-2.5 text-sm font-bold leading-6 text-red-800 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>

</html>