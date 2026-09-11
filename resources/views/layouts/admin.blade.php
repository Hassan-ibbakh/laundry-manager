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
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    @php
        $admin = auth('admin')->user();
        $dashboardActive = request()->routeIs('admin.dashboard');
        $laundriesActive = request()->routeIs('admin.laundries.*');
    @endphp

    {{-- Navigation ordinateur --}}
    <aside class="fixed inset-y-0 right-0 z-40 hidden w-64 flex-col bg-slate-900 text-slate-300 lg:flex">
        <div class="border-b border-white/10 px-5 py-6">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-lg shadow-blue-950/40">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/></svg>
                </div>
                <div><p class="font-black text-white">LaundryOS</p><p class="text-xs font-bold text-slate-400">مساحة الإدارة</p></div>
            </div>
        </div>

        <nav class="flex-1 space-y-2 px-3 py-5">
            <p class="px-3 pb-2 text-[10px] font-black tracking-wider text-slate-500">إدارة المنصة</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition {{ $dashboardActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30' : 'hover:bg-white/10 hover:text-white' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>لوحة التحكم</a>
            <a href="{{ route('admin.laundries.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition {{ $laundriesActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30' : 'hover:bg-white/10 hover:text-white' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/></svg>المغاسل</a>
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="mb-3 flex items-center gap-3 rounded-2xl bg-white/5 p-3"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-700 font-black text-white">{{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}</div><div class="min-w-0"><p class="truncate text-sm font-black text-white">{{ $admin->name ?? 'Admin' }}</p><p class="text-[11px] text-slate-400">مدير النظام</p></div></div>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-white/10 px-3 py-2.5 text-sm font-bold text-white transition hover:bg-rose-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>تسجيل الخروج</button></form>
        </div>
    </aside>

    <div class="lg:pr-64">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-3 lg:hidden"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg></div><p class="font-black text-slate-900">LaundryOS</p></div>
                <p class="hidden text-sm font-bold text-slate-500 lg:block">إدارة منصة المغاسل</p>
                <div class="flex items-center gap-2 sm:gap-3"><span class="hidden text-xs font-bold text-slate-400 sm:inline">{{ $admin->name ?? 'Admin' }}</span><form method="POST" action="{{ route('admin.logout') }}" class="lg:hidden">@csrf<button type="submit" aria-label="تسجيل الخروج" title="تسجيل الخروج" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button></form><a href="{{ route('admin.laundries.create') }}" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 sm:px-4 sm:text-sm">+ مغسلة جديدة</a></div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 pb-28 sm:px-6 lg:pb-8">
            @if(session('success'))<div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">✓</span>{{ session('success') }}</div>@endif
            @if(session('error'))<div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-800"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-500 text-white">!</span>{{ session('error') }}</div>@endif
            @yield('content')
        </main>
    </div>

    {{-- Navigation téléphone --}}
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 px-4 pb-[max(0.5rem,env(safe-area-inset-bottom))] pt-2 backdrop-blur lg:hidden"><div class="mx-auto grid max-w-sm grid-cols-2 gap-3"><a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 text-[11px] font-bold {{ $dashboardActive ? 'bg-blue-50 text-blue-600' : 'text-slate-500' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>لوحة التحكم</a><a href="{{ route('admin.laundries.index') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 text-[11px] font-bold {{ $laundriesActive ? 'bg-blue-50 text-blue-600' : 'text-slate-500' }}"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>المغاسل</a></div></nav>

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
