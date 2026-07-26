<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تتبع طلبك</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <meta http-equiv="refresh" content="10">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-violet-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-1">🧺 LaundryOS</h1>
        <p class="text-center text-gray-400 text-sm mb-6">تتبع طلبك</p>

        @php
            $labels = [
                'received'  => 'تم الاستلام',
                'cleaning'  => 'قيد الغسيل',
                'ready'     => 'جاهز للاستلام ✅',
                'delivered' => 'تم التسليم',
            ];
            $colors = [
                'received'  => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                'cleaning'  => 'bg-blue-100 text-blue-700 border-blue-300',
                'ready'     => 'bg-green-100 text-green-700 border-green-300',
                'delivered' => 'bg-gray-100 text-gray-700 border-gray-300',
            ];
        @endphp

        <div class="border rounded-xl p-4 mb-4 {{ $colors[$order->status] }}">
            <p class="text-lg font-bold text-center">{{ $labels[$order->status] }}</p>
        </div>

        <div class="text-sm text-gray-600 space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-400">رقم الطلب</span>
                <span class="font-mono font-medium">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">المغسلة</span>
                <span>{{ $order->laundry->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">الخدمة</span>
                <span>{{ $order->service }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">عدد القطع</span>
                <span>{{ $order->items->sum('quantity') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">المبلغ</span>
                <span class="font-bold text-blue-600">{{ $order->price }} د.م</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">تاريخ الاستلام</span>
                <span>{{ $order->received_at }}</span>
            </div>
        </div>

        <p class="text-center text-xs text-gray-300 mt-6">يتم تحديث الصفحة تلقائياً كل 10 ثوان</p>
    </div>
</body>
</html>