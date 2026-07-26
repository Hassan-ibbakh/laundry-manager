<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة المغسلة') — LaundryOS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-gradient-to-l from-blue-500 to-violet-500 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                {{-- Logo de la blanchisserie --}}
                @php
                    $laundry = auth('laundry')->user();
                @endphp
                @if($laundry && $laundry->logo_url)
                    <img src="{{ $laundry->logo_url }}" 
                         alt="Logo de {{ $laundry->name }}" 
                         class="h-10 w-10 object-cover rounded-full border-2 border-white/30 shadow-sm">
                @endif
                <h1 class="text-xl font-bold">
                 {{ $laundry ? $laundry->name : 'LaundryOS' }}
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('laundry.logout') }}">
                    @csrf
                    <button class="bg-white text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-gray-100 transition">
                        خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Nav Links -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 flex gap-6 py-2">
            <a href="{{ route('laundry.dashboard') }}"
               class="text-sm font-medium {{ request()->routeIs('laundry.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                الرئيسية
            </a>
            <a href="{{ route('laundry.orders.index') }}"
               class="text-sm font-medium {{ request()->routeIs('laundry.orders.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                الطلبات
            </a>
            <a href="{{ route('laundry.clients.index') }}"
               class="text-sm font-medium {{ request()->routeIs('laundry.clients.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                العملاء
            </a>
        </div>
    </div>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 rounded px-4 py-3 mb-4">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.dataset.submitted === 'true') {
                        event.preventDefault();
                        return;
                    }
                    form.dataset.submitted = 'true';
                    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function(button) {
                        button.disabled = true;
                    });
                });
            });
        });
    </script>
</body>
</html>