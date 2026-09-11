@extends('layouts.admin')

@section('title', 'لوحة الإدارة')

@section('content')
<div class="mx-auto max-w-7xl space-y-7" dir="rtl">
    <header class="flex flex-wrap items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-lg shadow-slate-200">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900">لوحة الإدارة</h1>
                <p class="mt-1 text-sm font-medium text-slate-500">نظرة شاملة على المغاسل والعمليات في المنصة</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-500 sm:inline-block">{{ now()->format('d/m/Y H:i') }}</span>
            <a href="{{ route('admin.laundries.create') }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700">+ مغسلة جديدة</a>
        </div>
    </header>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-3xl border border-blue-100 bg-blue-50/70 p-5 shadow-sm"><div class="flex items-start justify-between"><div><p class="text-xs font-black text-blue-600">إجمالي المغاسل</p><p class="mt-2 text-3xl font-black text-blue-950">{{ number_format($stats['total_laundries']) }}</p><p class="mt-2 text-xs font-bold text-blue-500">كل الحسابات المسجلة</p></div><span class="rounded-2xl bg-white p-3 text-blue-600 shadow-sm"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 10h.01M15 10h.01"/></svg></span></div></article>
        <article class="rounded-3xl border border-emerald-100 bg-emerald-50/70 p-5 shadow-sm"><div class="flex items-start justify-between"><div><p class="text-xs font-black text-emerald-600">المغاسل النشطة</p><p class="mt-2 text-3xl font-black text-emerald-950">{{ number_format($stats['active_laundries']) }}</p><p class="mt-2 text-xs font-bold text-emerald-600">{{ $stats['active_percentage'] }}% من الإجمالي</p></div><span class="rounded-2xl bg-white p-3 text-emerald-600 shadow-sm"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span></div></article>
        <article class="rounded-3xl border border-violet-100 bg-violet-50/70 p-5 shadow-sm"><div class="flex items-start justify-between"><div><p class="text-xs font-black text-violet-600">إجمالي الطلبات</p><p class="mt-2 text-3xl font-black text-violet-950">{{ number_format($stats['total_orders']) }}</p><p class="mt-2 text-xs font-bold text-violet-500">طلبات كل المغاسل</p></div><span class="rounded-2xl bg-white p-3 text-violet-600 shadow-sm"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></span></div></article>
        <article class="rounded-3xl border border-amber-100 bg-amber-50/70 p-5 shadow-sm"><div class="flex items-start justify-between"><div><p class="text-xs font-black text-amber-700">نشاط اليوم</p><p class="mt-2 text-3xl font-black text-amber-950">{{ number_format($stats['today_orders']) }}</p><p class="mt-2 text-xs font-bold text-amber-600">{{ number_format($stats['total_clients']) }} عميل في المنصة</p></div><span class="rounded-2xl bg-white p-3 text-amber-600 shadow-sm"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 2M22 12a10 10 0 11-20 0 10 10 0 0120 0z"/></svg></span></div></article>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
            <div><h2 class="font-black text-slate-900">أحدث المغاسل</h2><p class="mt-1 text-sm text-slate-500">إدارة الحسابات المسجلة ومتابعة نشاطها</p></div>
            <a href="{{ route('admin.laundries.index') }}" class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-200">عرض جميع المغاسل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-right text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-xs font-black text-slate-500"><tr><th class="px-6 py-4">المغسلة</th><th class="px-6 py-4">معلومات التواصل</th><th class="px-6 py-4 text-center">الطلبات</th><th class="px-6 py-4 text-center">الحالة</th><th class="px-6 py-4">تاريخ التسجيل</th><th class="px-6 py-4 text-left">إجراء</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laundries as $laundry)
                        <tr class="transition-colors hover:bg-slate-50/70">
                            <td class="px-6 py-4"><div class="flex items-center gap-3">@if($laundry->logo_url)<img src="{{ $laundry->logo_url }}" alt="{{ $laundry->name }}" class="h-10 w-10 rounded-xl border border-slate-200 object-cover" loading="lazy" decoding="async">@else<div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-xs font-black text-blue-600">{{ strtoupper(substr($laundry->name, 0, 2)) }}</div>@endif<div><p class="font-black text-slate-800">{{ $laundry->name }}</p><p class="mt-0.5 text-xs text-slate-400">#{{ $laundry->id }}</p></div></div></td>
                            <td class="px-6 py-4"><p class="text-slate-700">{{ $laundry->email }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $laundry->phone }}</p></td>
                            <td class="px-6 py-4 text-center"><span class="rounded-xl bg-violet-50 px-2.5 py-1 text-xs font-black text-violet-700">{{ number_format($laundry->orders_count) }}</span></td>
                            <td class="px-6 py-4 text-center">@if($laundry->is_active)<span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>نشط</span>@else<span class="inline-flex items-center gap-1.5 rounded-xl bg-rose-50 px-2.5 py-1 text-xs font-black text-rose-700"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>غير نشط</span>@endif</td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-500">{{ $laundry->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-left"><a href="{{ route('admin.laundries.edit', $laundry->id) }}" class="inline-flex rounded-xl bg-blue-50 px-3 py-2 text-xs font-bold text-blue-600 transition hover:bg-blue-100">إدارة</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center"><div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg></div><p class="font-bold text-slate-600">لا توجد مغاسل مسجلة بعد</p><a href="{{ route('admin.laundries.create') }}" class="mt-2 inline-block text-sm font-bold text-blue-600 hover:underline">إنشاء أول مغسلة</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laundries->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $laundries->links() }}</div>@endif
    </section>
</div>
@endsection
