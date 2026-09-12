@extends('layouts.laundry')
@section('title', 'تفاصيل الطلب')
@section('content')

@php
    $labels = [
        'received'  => 'تم الاستلام',
        'cleaning'  => 'قيد الغسيل',
        'ready'     => 'جاهز للاستلام',
        'delivered' => 'تم التسليم',
    ];
    $badgeDot = [
        'received'  => 'bg-amber-500',
        'cleaning'  => 'bg-blue-500',
        'ready'     => 'bg-green-500',
        'delivered' => 'bg-gray-400',
    ];
@endphp

<div class="max-w-2xl lg:max-w-4xl mx-auto">

    <a href="{{ route('laundry.orders.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
        رجوع إلى القائمة
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">

        <div class="flex flex-wrap gap-3 justify-between items-start mb-6 pb-6 border-b border-gray-100">
            <div>
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900">{{ $order->order_number }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $order->received_at->format('Y-m-d') }}</p>
            </div>
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-100">
                <span class="w-2 h-2 rounded-full {{ $badgeDot[$order->status] }}"></span>
                {{ $labels[$order->status] }}
            </span>
        </div>

        {{-- Informations client --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 text-sm mb-6">
            <div>
                <p class="text-gray-400 text-xs mb-1">العميل</p>
                <p class="font-medium text-gray-900">{{ $order->client->name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">الهاتف</p>
                <p class="font-medium text-gray-900" dir="ltr">{{ $order->client->phone }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">المبلغ الإجمالي</p>
                <p class="font-bold text-blue-600 text-lg">{{ number_format($order->price, 2) }} د.م</p>
            </div>
        </div>

        @if($order->delivery_required)
            <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm">
                <div class="flex items-center gap-2 font-bold text-blue-800">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    <span>طلب بالتوصيل</span>
                </div>
                <p class="mt-2 text-blue-700">
                    <span class="font-medium">عنوان التوصيل:</span>
                    {{ $order->delivery_address ?: 'لم يتم إدخال عنوان التوصيل.' }}
                </p>
            </div>
        @endif

        {{-- Liste des articles avec service --}}
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">القطع</h3>
            <div class="space-y-2">
                @forelse($order->items as $item)
                    <div class="flex flex-wrap gap-2 justify-between items-center bg-gray-50 border border-gray-100 rounded-xl px-4 py-3">
                        <div class="min-w-0 break-words">
                            <span class="font-medium text-gray-900">{{ $item->pieces_type }}</span>
                            @if($item->pieces_color)
                                <span class="text-sm text-gray-500"> - {{ $item->pieces_color }}</span>
                            @endif
                            <span class="text-xs text-gray-400 mx-1">({{ $item->service }})</span>
                            <span class="text-sm text-gray-500 mx-2">({{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} د.م)</span>
                        </div>
                        <span class="shrink-0 font-bold text-gray-900">{{ number_format($item->total_price, 2) }} د.م</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm px-1">لا توجد قطع مسجلة</p>
                @endforelse
            </div>
        </div>

        {{-- Mise à jour du statut --}}
        <div class="mb-6 rounded-xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-xs font-medium text-gray-500 mb-3">تحديث حالة الطلب</p>
            <form method="POST" action="{{ route('laundry.orders.status', $order->id) }}">
                @csrf @method('PATCH')
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <select name="status"
                        class="w-full sm:w-auto border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="received"  {{ $order->status == 'received'  ? 'selected' : '' }}>تم الاستلام</option>
                        <option value="cleaning"  {{ $order->status == 'cleaning'  ? 'selected' : '' }}>قيد الغسيل</option>
                        <option value="ready"     {{ $order->status == 'ready'     ? 'selected' : '' }}>جاهز للاستلام</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    </select>
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        تحديث الحالة
                    </button>
                </div>
            </form>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3 pt-2">
            <a href="{{ route('laundry.orders.ticket', $order->id) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i>
                طباعة التذكرة
            </a>
            <a href="{{ route('laundry.orders.pdf', $order->id) }}"
               class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors">
                <i data-lucide="download" class="w-4 h-4 text-red-600"></i>
                تحميل PDF
            </a>
            <a href="{{ route('laundry.orders.whatsapp', $order->id) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors">
                <i data-lucide="message-circle" class="w-4 h-4 text-green-600"></i>
                إرسال واتساب
            </a>
            <a href="{{ route('tracking.show', $order->tracking_token) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors">
                <i data-lucide="link" class="w-4 h-4 text-blue-600"></i>
                رابط التتبع
            </a>
        </div>
    </div>
</div>

@endsection