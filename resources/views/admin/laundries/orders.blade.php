@extends('layouts.admin')
@section('title', 'طلبات ' . $laundry->name)
@section('content')

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('admin.laundries.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            ← العودة إلى قائمة المغاسل
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">طلبات {{ $laundry->name }}</h2>
        <p class="text-sm text-gray-500 mt-1">جميع الطلبات المسجلة لهذه المغسلة</p>
    </div>
    <div class="bg-blue-50 text-blue-700 px-4 py-3 rounded-lg text-sm font-semibold">
        {{ $orders->total() }} طلب
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-right">رقم الطلب</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">الخدمة</th>
                    <th class="px-4 py-3 text-right">السعر</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                @php
                    $colors = [
                        'received' => 'bg-yellow-100 text-yellow-700',
                        'cleaning' => 'bg-blue-100 text-blue-700',
                        'ready' => 'bg-green-100 text-green-700',
                        'delivered' => 'bg-gray-100 text-gray-700',
                    ];
                    $labels = [
                        'received' => 'تم الاستلام',
                        'cleaning' => 'قيد الغسيل',
                        'ready' => 'جاهز',
                        'delivered' => 'تم التسليم',
                    ];
                @endphp
                <tr class="hover:bg-blue-50/50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-gray-700">{{ $order->order_number }}</td>
                    <td class="px-4 py-3">{{ $order->client->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $order->service }}</td>
                    <td class="px-4 py-3">{{ number_format((float) $order->price, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $labels[$order->status] ?? $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <p class="text-lg font-medium">لا توجد طلبات لهذه المغسلة</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-6 py-4 border-t bg-gray-50/50">
        {{ $orders->links() }}
    </div>
    @endif

    <div class="px-6 py-3 text-xs text-gray-400 border-t">
        الإجمالي: {{ $orders->total() }} طلب
    </div>
</div>

@endsection
