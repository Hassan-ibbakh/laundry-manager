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
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    @php
        $laundry = auth('laundry')->user();
        $dashboardActive = request()->routeIs('laundry.dashboard');
        $ordersActive = request()->routeIs('laundry.orders.*');
        $clientsActive = request()->routeIs('laundry.clients.*');
    @endphp

    {{-- Barre latérale : ordinateur --}}
    <aside class="hidden lg:flex fixed inset-y-0 right-0 z-40 w-64 flex-col border-l border-slate-200 bg-white">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-6">
            @if($laundry && $laundry->logo_url)
                <img src="{{ $laundry->logo_url }}" alt="{{ $laundry->name }}" class="h-12 w-12 shrink-0 rounded-2xl object-cover border border-slate-200" loading="lazy" decoding="async">
            @else
                <div class="h-12 w-12 shrink-0 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg font-black shadow-lg shadow-blue-200">{{ strtoupper(substr($laundry->name ?? 'L', 0, 2)) }}</div>
            @endif
            <div class="min-w-0"><img src="{{ asset('images/logo.png') }}" alt="Nadif" class="h-8 w-auto object-contain" width="220" height="70"><p class="text-xs font-bold text-slate-400">إدارة المغسلة</p></div>
        </div>

        <nav class="flex-1 space-y-2 px-3 py-5">
            <p class="px-3 pb-2 text-[10px] font-black tracking-wider text-slate-400">القائمة الرئيسية</p>
            <a href="{{ route('laundry.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition {{ $dashboardActive ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-600' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg> التقرير اليومي
            </a>
            <a href="{{ route('laundry.orders.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition {{ $ordersActive ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-600' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/></svg> الطلبات
            </a>
            <a href="{{ route('laundry.clients.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition {{ $clientsActive ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-slate-100 hover:text-blue-600' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg> العملاء
            </a>
        </nav>

        <div class="border-t border-slate-100 p-4">
            <div class="mb-3 flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> النظام متصل</div>
            <form method="POST" action="{{ route('laundry.logout') }}">@csrf<button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-bold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> تسجيل الخروج</button></form>
        </div>
    </aside>

    <div class="lg:pr-64">
        {{-- En-tête : mobile et contenu ordinateur --}}
        <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-3 lg:hidden">
                    @if($laundry && $laundry->logo_url)
                        <img src="{{ $laundry->logo_url }}" alt="{{ $laundry->name }}" class="h-10 w-10 rounded-xl object-cover border border-slate-200" loading="lazy" decoding="async">
                    @else
                        <div class="h-10 w-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black">{{ strtoupper(substr($laundry->name ?? 'L', 0, 2)) }}</div>
                    @endif
                    <img src="{{ asset('images/logo.png') }}" alt="Nadif" class="h-8 w-auto object-contain" width="220" height="70">
                </div>
                <div class="hidden lg:block"><p class="text-sm font-bold text-slate-500">مرحباً، {{ $laundry->name ?? 'LaundryOS' }}</p></div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('laundry.logout') }}" class="lg:hidden">@csrf<button type="submit" aria-label="تسجيل الخروج" title="تسجيل الخروج" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button></form>
                    <span class="hidden sm:inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-bold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> متصل</span>
                    <a href="{{ route('laundry.orders.create') }}" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 sm:px-4 sm:text-sm">+ طلب جديد</a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 pb-28 sm:px-6 lg:pb-8">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">✓</span>{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>

    {{-- Navigation fixe : téléphone --}}
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 px-4 pb-[max(0.5rem,env(safe-area-inset-bottom))] pt-2 backdrop-blur lg:hidden">
        <div class="mx-auto grid max-w-md grid-cols-3 gap-2">
            <a href="{{ route('laundry.dashboard') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 text-[11px] font-bold {{ $dashboardActive ? 'bg-blue-50 text-blue-600' : 'text-slate-500' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>التقرير</a>
            <a href="{{ route('laundry.orders.index') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 text-[11px] font-bold {{ $ordersActive ? 'bg-blue-50 text-blue-600' : 'text-slate-500' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>الطلبات</a>
            <a href="{{ route('laundry.clients.index') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 text-[11px] font-bold {{ $clientsActive ? 'bg-blue-50 text-blue-600' : 'text-slate-500' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>العملاء</a>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (form.dataset.submitted === 'true') { event.preventDefault(); return; }
                    form.dataset.submitted = 'true';
                    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => button.disabled = true);
                });
            });
        });
    </script>
</body>
</html>
