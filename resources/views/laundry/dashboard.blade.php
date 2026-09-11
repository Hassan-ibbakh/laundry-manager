@extends('layouts.laundry')

@section('title', 'التقرير اليومي')

@section('content')
<style>
    @media print { .no-print { display: none !important; } body { background: #fff !important; } }
</style>

<div class="max-w-6xl mx-auto pb-16" dir="rtl">
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900">التقرير اليومي</h1>
                <p class="text-sm font-bold text-slate-500 mt-0.5">متابعة المداخيل والعمليات اليومية</p>
            </div>
        </div>
        <div class="flex items-center gap-2 no-print">
            <form method="GET" action="{{ route('laundry.dashboard') }}">
                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
            </form>
            @if($selectedDate !== now()->toDateString())
                <a href="{{ route('laundry.dashboard') }}" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200">اليوم</a>
            @endif
            <button type="button" onclick="window.print()" class="p-2.5 bg-slate-50 border border-slate-200 text-slate-600 hover:text-blue-600 rounded-xl" title="طباعة التقرير">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            </button>
            <a href="{{ route('laundry.orders.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm">+ طلب جديد</a>
        </div>
    </header>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-blue-50/70 p-6 rounded-3xl border border-blue-100 shadow-sm"><p class="text-blue-600 text-xs font-black mb-2">إجمالي المداخيل</p><p class="text-3xl font-black text-blue-900">{{ number_format($totalRevenue, 2) }} <span class="text-lg">د.م</span></p><p class="text-[11px] font-bold text-blue-500 mt-2">طلبات يوم {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</p></div>
        <div class="bg-emerald-50/70 p-6 rounded-3xl border border-emerald-100 shadow-sm"><p class="text-emerald-600 text-xs font-black mb-2">تم قبضها</p><p class="text-3xl font-black text-emerald-900">{{ number_format($paidRevenue, 2) }} <span class="text-lg">د.م</span></p><p class="text-[11px] font-bold text-emerald-600 mt-2">المبالغ المدفوعة</p></div>
        <div class="bg-rose-50/70 p-6 rounded-3xl border border-rose-100 shadow-sm"><p class="text-rose-600 text-xs font-black mb-2">في الذمة</p><p class="text-3xl font-black text-rose-900">{{ number_format($unpaidRevenue, 2) }} <span class="text-lg">د.م</span></p><p class="text-[11px] font-bold text-rose-500 mt-2">مبالغ غير مدفوعة</p></div>
        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 shadow-sm"><p class="text-slate-600 text-xs font-black mb-2">عدد الطلبات</p><p class="text-3xl font-black text-slate-900">{{ $dailyCount }} <span class="text-lg">طلب</span></p><p class="text-[11px] font-bold text-slate-400 mt-2">الطلبات المسجلة</p></div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <section class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-black text-slate-900">تفاصيل العمليات ({{ $dailyCount }})</h2>
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-sm">
                        <thead class="bg-slate-50 text-slate-400 text-xs font-black"><tr><th class="px-5 py-4">الرقم / العميل</th><th class="px-5 py-4">القطع</th><th class="px-5 py-4 text-center">الحالة</th><th class="px-5 py-4 text-center">الدفع</th><th class="px-5 py-4 text-left">المبلغ</th><th class="px-5 py-4 text-center no-print">تفاصيل</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($dailyOrders as $order)
                                @php($statusClasses = ['received'=>'bg-amber-100 text-amber-800','cleaning'=>'bg-blue-100 text-blue-800','ready'=>'bg-emerald-100 text-emerald-800','delivered'=>'bg-slate-100 text-slate-700'])
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-4"><p class="font-mono font-black text-slate-900">{{ $order->order_number }}</p><p class="text-xs font-bold text-slate-500 mt-0.5">{{ $order->client->name ?? 'بدون اسم' }}</p></td>
                                    <td class="px-5 py-4 text-xs font-bold text-slate-600">{{ $order->items->sum('quantity') }} قطع</td>
                                    <td class="px-5 py-4 text-center"><span class="inline-block px-2.5 py-1 rounded-xl text-[11px] font-black {{ $statusClasses[$order->status] ?? 'bg-slate-100' }}">{{ $statuses[$order->status]['label'] ?? $order->status }}</span></td>
                                    <td class="px-5 py-4 text-center"><span class="inline-block px-2.5 py-1 rounded-xl text-[11px] font-black {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">{{ $order->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}</span></td>
                                    <td class="px-5 py-4 text-left font-black text-blue-600">{{ number_format($order->price, 2) }} د.م</td>
                                    <td class="px-5 py-4 text-center no-print"><a href="{{ route('laundry.orders.show', $order->id) }}" class="text-blue-600 hover:underline text-xs font-bold">عرض</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400"><p class="font-bold text-slate-600">لا توجد عمليات مسجلة لهذا التاريخ</p><p class="text-xs mt-1">اختر يوماً آخر أو أنشئ طلباً جديداً</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <aside class="space-y-6">
            <section class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm"><h3 class="text-sm font-black text-slate-900 mb-5">حالات طلبات اليوم</h3><div class="space-y-4">@foreach($statuses as $status)<div class="space-y-1.5"><div class="flex justify-between text-xs font-black"><span class="text-slate-600">{{ $status['label'] }}</span><span class="text-slate-900">{{ $status['count'] }} <span class="text-[10px] text-slate-400">({{ $status['percentage'] }}%)</span></span></div><div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-full {{ $status['color'] }} rounded-full" style="width: {{ $status['percentage'] }}%"></div></div></div>@endforeach</div></section>
            <section class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl shadow-slate-200"><p class="text-xs font-black tracking-wider opacity-60 mb-4">ملخص القطع</p><p class="text-4xl font-black">{{ $totalPieces }}</p><p class="text-xs text-slate-400 font-bold mt-1">إجمالي القطع المعالجة اليوم</p></section>
            <section class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm"><div class="flex items-center justify-between mb-4"><h3 class="text-sm font-black text-slate-900">العملاء الجدد</h3><a href="{{ route('laundry.clients.index') }}" class="text-xs font-bold text-blue-600 hover:underline">عرض الكل</a></div><div class="divide-y divide-slate-100">@forelse($recentClients as $client)<div class="py-3 flex items-center justify-between first:pt-0"><div><p class="font-bold text-slate-900 text-xs">{{ $client->name }}</p><p class="text-[11px] font-mono text-slate-400 mt-0.5">{{ $client->phone }}</p></div><span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">{{ $client->created_at->diffForHumans() }}</span></div>@empty<p class="text-xs text-slate-400 text-center py-4">لا يوجد عملاء جدد</p>@endforelse</div></section>
            <div class="grid grid-cols-2 gap-3 text-center"><div class="bg-white border border-slate-200 rounded-2xl p-3"><p class="text-xl font-black text-slate-900">{{ $globalStats['all_orders'] }}</p><p class="text-[10px] text-slate-500 font-bold">كل الطلبات</p></div><div class="bg-white border border-slate-200 rounded-2xl p-3"><p class="text-xl font-black text-slate-900">{{ $globalStats['all_clients'] }}</p><p class="text-[10px] text-slate-500 font-bold">كل العملاء</p></div></div>
        </aside>
    </div>
</div>
@endsection
