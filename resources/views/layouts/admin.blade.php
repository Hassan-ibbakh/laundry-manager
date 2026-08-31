<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الإدارة') — LaundryOS</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-gradient-to-l from-blue-600 to-violet-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap gap-3 justify-between items-center">
            <h1 class="min-w-0 truncate text-xl font-bold">🧺 LaundryOS — الإدارة</h1>
            <div class="shrink-0 flex items-center gap-4">
                <span class="max-w-[10rem] truncate text-sm">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="bg-white text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-gray-100 transition-colors">
                        خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Nav Links -->
    <div class="bg-white shadow-sm border-b">
         <div class="max-w-7xl mx-auto px-4 flex flex-wrap items-center gap-x-8 gap-y-2 py-2">
            <a href="{{ route('admin.dashboard') }}"
             class="inline-flex shrink-0 whitespace-nowrap px-1 py-1 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                الرئيسية
            </a>
            <a href="{{ route('admin.laundries.index') }}"
                    class="inline-flex shrink-0 whitespace-nowrap px-1 py-1 text-sm font-medium {{ request()->routeIs('admin.laundries.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                المغاسل
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
        
        @if(session('error'))
            <div class="bg-red-100 text-red-800 border border-red-300 rounded px-4 py-3 mb-4">
                {{ session('error') }}
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