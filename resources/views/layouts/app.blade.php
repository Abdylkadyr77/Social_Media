<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> {{-- Mobil uyumluluk için --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sosyal Medya') }}</title>

    <!-- Tailwind CSS CDN - Bu, modern stil sınıflarını yükler -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fontlar - Laravel'in varsayılan fontları -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Eğer Vite veya başka bir JavaScript/CSS derleyicisi kullanıyorsan bu satırlar gerekli olabilir.
         Şimdilik Tailwind CDN kullandığımız için yorum satırı olarak kalabilir. -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="font-sans antialiased bg-gray-100">
    <div id="app">
        <!-- Modern Navigasyon Çubuğu (Header) -->
        <nav class="bg-white shadow-md py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <!-- Sol Taraf: Logo / Uygulama Adı -->
                <a class="text-2xl font-bold text-gray-800 hover:text-gray-600" href="{{ url('/') }}">
                    Sosyal Medya
                </a>

                <!-- Sağ Taraf: Navigasyon Linkleri -->
                <div class="flex items-center space-x-6">
                    @guest {{-- Kullanıcı giriş yapmamışsa --}}
                        @if (Route::has('login'))
                            <a class="text-gray-600 hover:text-gray-800 font-medium" href="{{ route('login') }}">Giriş Yap</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="text-gray-600 hover:text-gray-800 font-medium" href="{{ route('register') }}">Kayıt Ol</a>
                        @endif
                    @else {{-- Kullanıcı giriş yapmışsa --}}
                        <!-- Anasayfa Linki -->
                        <a class="text-gray-600 hover:text-gray-800 font-medium" href="{{ route('home') }}">Anasayfa</a>

                        <!-- Kullanıcının Profil Linki (Kullanıcı adını gösterir) -->
                        <a class="text-gray-600 hover:text-gray-800 font-medium" href="{{ route('profile.show.own') }}">
                            {{ Auth::user()->username }}
                        </a>

                        <!-- Çıkış Yap Butonu -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800 font-medium focus:outline-none">
                                Çıkış Yap
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content') {{-- Sayfa içeriği bu kısımda gösterilir --}}
        </main>
    </div>
</body>
</html>
