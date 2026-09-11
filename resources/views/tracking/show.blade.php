<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تتبع طلبك - Nadif</title>
    <meta name="theme-color" content="#2563eb">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <meta http-equiv="refresh" content="10">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Cairo', 'sans-serif'] } } } }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50 to-white flex items-start sm:items-center justify-center p-4 py-10 sm:py-4">

    @php
        $labels = [
            'received'  => 'تم الاستلام',
            'cleaning'  => 'قيد الغسيل',
            'ready'     => 'جاهز للاستلام',
            'delivered' => 'تم التسليم',
        ];
        $icons = [
            'received'  => 'clipboard-list',
            'cleaning'  => 'droplets',
            'ready'     => 'package-check',
            'delivered' => 'check-check',
        ];
        $steps = array_keys($labels);
        $currentIndex = array_search($order->status, $steps);
        $isReady = $order->status === 'ready';
    @endphp

    <div class="w-full max-w-md md:max-w-2xl lg:max-w-4xl">

        <div class="text-center mb-6">
            <div class="text-2xl md:text-3xl font-black text-blue-600 tracking-tight">Nadif</div>
            <p class="text-gray-400 text-sm mt-1">تتبع الطلب</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

            <div class="px-6 sm:px-8 lg:px-10 pt-7 pb-6 text-center {{ $isReady ? 'bg-green-600' : 'bg-blue-600' }}">
                <p class="text-white/80 text-xs font-medium mb-1">حالة طلبك</p>
                <p class="text-white text-xl lg:text-2xl font-bold">{{ $labels[$order->status] ?? $order->status }}</p>
                <p class="text-white/70 text-xs mt-2 font-mono tracking-wide">{{ $order->order_number }}</p>
                <p class="text-white/85 text-sm mt-3 font-medium">
                    المصبنة: {{ $order->laundry?->name ?? 'غير متوفر' }}
                </p>
            </div>

            <div class="lg:grid lg:grid-cols-5 lg:divide-x lg:divide-x-reverse lg:divide-gray-100">

            <div class="px-6 sm:px-8 lg:px-8 py-7 lg:col-span-2">
                <div class="flex flex-col">
                    @foreach ($steps as $i => $key)
                        <div class="flex gap-4 items-stretch">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition-colors
                                    @if ($i < $currentIndex) bg-blue-600 text-white
                                    @elseif ($i === $currentIndex) bg-blue-600 text-white ring-4 ring-blue-100
                                    @else bg-gray-100 text-gray-400
                                    @endif">
                                    <i data-lucide="{{ $icons[$key] }}" class="w-[18px] h-[18px]"></i>
                                </div>
                                @if (!$loop->last)
                                    <div class="w-0.5 flex-1 min-h-[1.75rem] {{ $i < $currentIndex ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                            <div class="pt-2 {{ $loop->last ? '' : 'pb-6' }} text-right flex-1">
                                <p class="font-bold text-sm {{ $i <= $currentIndex ? 'text-gray-900' : 'text-gray-400' }}">
                                    {{ $labels[$key] }}
                                </p>
                                @if ($i === $currentIndex)
                                    <p class="text-xs text-blue-600 mt-1 flex items-center gap-1.5 justify-end">
                                        جاري الآن
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-3">

            <div class="px-6 sm:px-8 lg:px-8 pt-7 lg:pt-7 pb-6">
                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-900">{{ $order->laundry->name }}</span>
                        <span class="text-gray-400">المصبنة</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">{{ $order->service }}</span>
                        <span class="text-gray-400">الخدمة</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">{{ $order->items->sum('quantity') }}</span>
                        <span class="text-gray-400">عدد القطع</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">{{ $order->received_at }}</span>
                        <span class="text-gray-400">تاريخ الاستلام</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="font-black text-blue-600 text-lg">{{ $order->price }} د.م</span>
                        <span class="text-gray-400">المبلغ الإجمالي</span>
                    </div>
                </div>
            </div>

            <div class="px-6 sm:px-8 pb-7">
                <h2 class="text-sm font-bold text-gray-900 mb-3">تفاصيل الطلب</h2>
                <div class="space-y-2">
                    @forelse ($order->items as $item)
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-3.5 text-sm flex items-start justify-between gap-3">
                            <p class="shrink-0 font-bold text-gray-700 whitespace-nowrap">
                                {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} د.م
                            </p>
                            <div class="min-w-0 text-right">
                                <p class="font-medium text-gray-800 break-words">{{ $item->pieces_type }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $item->service }}
                                    @if ($item->pieces_color)
                                        · {{ $item->pieces_color }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">لا توجد تفاصيل للطلب</p>
                    @endforelse
                </div>
            </div>

            <div class="px-6 sm:px-8 pb-8">
                <a href="{{ route('tracking.pdf', $order->tracking_token) }}"
                   class="flex items-center justify-center gap-2 w-full rounded-xl bg-gray-900 px-4 py-3.5 text-center text-sm font-bold text-white hover:bg-gray-800 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    تحميل نسخة PDF
                </a>
            </div>

            </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5 flex items-center justify-center gap-1.5">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            الصفحة تتحدث تلقائياً كل 10 ثوان
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>