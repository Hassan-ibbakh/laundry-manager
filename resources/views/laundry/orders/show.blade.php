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
    $colors = [
        'received'  => 'bg-yellow-100 text-yellow-700',
        'cleaning'  => 'bg-blue-100 text-blue-700',
        'ready'     => 'bg-green-100 text-green-700',
        'delivered' => 'bg-gray-100 text-gray-700',
    ];
@endphp

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6 mb-4">
        <div class="flex flex-wrap gap-3 justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $order->order_number }}</h2>
                <p class="text-sm text-gray-400">{{ $order->received_at->format('Y-m-d') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm {{ $colors[$order->status] }}">
                {{ $labels[$order->status] }}
            </span>
        </div>

        {{-- Informations client --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
            <div><p class="text-gray-400">العميل</p><p class="font-medium">{{ $order->client->name }}</p></div>
            <div><p class="text-gray-400">الهاتف</p><p class="font-medium">{{ $order->client->phone }}</p></div>
            <div><p class="text-gray-400">المبلغ الإجمالي</p><p class="font-bold text-blue-600 text-lg">{{ number_format($order->price, 2) }} د.م</p></div>
        </div>

        {{-- Liste des articles avec service --}}
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">القطع</h3>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                @forelse($order->items as $item)
                    <div class="flex flex-wrap gap-2 justify-between items-center border-b border-gray-200 pb-2 last:border-0">
                        <div class="min-w-0 break-words">
                            <span class="font-medium">{{ $item->pieces_type }}</span>
                            @if($item->pieces_color)
                                <span class="text-sm text-gray-500"> - {{ $item->pieces_color }}</span>
                            @endif
                            <span class="text-xs text-gray-400 mx-1">({{ $item->service }})</span>
                            <span class="text-sm text-gray-500 mx-2">({{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} د.م)</span>
                        </div>
                        <span class="shrink-0 font-bold">{{ number_format($item->total_price, 2) }} د.م</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">لا توجد قطع مسجلة</p>
                @endforelse
            </div>
        </div>

        {{-- Mise à jour du statut --}}
        <form method="POST" action="{{ route('laundry.orders.status', $order->id) }}" class="mb-4">
            @csrf @method('PATCH')
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <select name="status"
                    class="w-full sm:w-auto border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="received"  {{ $order->status == 'received'  ? 'selected' : '' }}>تم الاستلام</option>
                    <option value="cleaning"  {{ $order->status == 'cleaning'  ? 'selected' : '' }}>قيد الغسيل</option>
                    <option value="ready"     {{ $order->status == 'ready'     ? 'selected' : '' }}>جاهز للاستلام</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                </select>
                <button type="submit"
                    class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    تحديث الحالة
                </button>
            </div>
        </form>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3 pt-4 border-t">
            <a href="{{ route('laundry.orders.pdf', $order->id) }}"
               class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm hover:bg-red-200 transition">
                تحميل PDF
            </a>
            <a href="{{ route('laundry.orders.whatsapp', $order->id) }}" target="_blank"
               class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm hover:bg-green-200 transition">
                💬 إرسال واتساب
            </a>
            <a href="{{ route('tracking.show', $order->tracking_token) }}" target="_blank"
               class="bg-violet-100 text-violet-700 px-4 py-2 rounded-lg text-sm hover:bg-violet-200 transition">
                🔗 رابط التتبع
            </a>
        </div>
    </div>

    <a href="{{ route('laundry.orders.index') }}" class="text-sm text-gray-500 hover:underline transition">
        ← رجوع إلى القائمة
    </a>
</div>

@endsection