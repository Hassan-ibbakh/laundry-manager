@extends('layouts.laundry')
@section('title', 'الطلبات')
@section('content')

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">📦 الطلبات</h2>
        <p class="text-sm text-gray-500 mt-1">قائمة جميع الطلبات</p>
    </div>
    <a href="{{ route('laundry.orders.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1">
        <span>+</span> طلب جديد
    </a>
</div>

<!-- Filters -->
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="بحث برقم الطلب أو اسم العميل..."
        class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">كل الحالات</option>
        <option value="received"  {{ request('status') == 'received'  ? 'selected' : '' }}>📥 تم الاستلام</option>
        <option value="cleaning"  {{ request('status') == 'cleaning'  ? 'selected' : '' }}>🧺 قيد الغسيل</option>
        <option value="ready"     {{ request('status') == 'ready'     ? 'selected' : '' }}>✅ جاهز</option>
        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>📦 تم التسليم</option>
    </select>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition">
        🔍 بحث
    </button>
    @if(request()->has('search') || request()->has('status'))
        <a href="{{ route('laundry.orders.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
            إعادة تعيين
        </a>
    @endif
</form>

<!-- Orders Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-right">رقم الطلب</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">الخدمة</th>
                    <th class="px-4 py-3 text-right">المبلغ</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-gray-700">{{ $order->order_number }}</td>
                    <td class="px-4 py-3">{{ $order->client->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $order->service }}</td>
                    <td class="px-4 py-3 font-semibold">{{ number_format($order->price, 2) }} د.م</td>
                    <td class="px-4 py-3">
                        @php
                            $colors = [
                                'received'  => 'bg-yellow-100 text-yellow-700',
                                'cleaning'  => 'bg-blue-100 text-blue-700',
                                'ready'     => 'bg-green-100 text-green-700',
                                'delivered' => 'bg-gray-100 text-gray-700',
                            ];
                            $labels = [
                                'received'  => '📥 تم الاستلام',
                                'cleaning'  => '🧺 قيد الغسيل',
                                'ready'     => '✅ جاهز',
                                'delivered' => '📦 تم التسليم',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $labels[$order->status] ?? $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('laundry.orders.show', $order->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">
                                👁️ عرض
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('laundry.orders.whatsapp', $order->id) }}"
                               class="text-green-600 hover:text-green-800 text-xs font-medium transition" target="_blank">
                                💬
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('laundry.orders.pdf', $order->id) }}"
                               class="text-red-600 hover:text-red-800 text-xs font-medium transition" target="_blank">
                                📄
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">📦</div>
                        <p class="text-lg font-medium">لا توجد طلبات</p>
                        <p class="text-sm mt-1">قم بإنشاء أول طلب لك</p>
                        <a href="{{ route('laundry.orders.create') }}" class="text-blue-600 hover:underline text-sm mt-3 inline-block">
                            إنشاء أول طلب →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t bg-gray-50/50">
        {{ $orders->links() }}
    </div>
    @endif
    
    <!-- Total -->
    <div class="px-6 py-3 text-xs text-gray-400 border-t flex justify-between items-center">
        <span>إجمالي : {{ $orders->total() }} طلب</span>
        <span>الصفحة {{ $orders->currentPage() }} من {{ $orders->lastPage() }}</span>
    </div>
</div>

@endsection