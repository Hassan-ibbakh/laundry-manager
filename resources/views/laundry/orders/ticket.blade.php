<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }
        body { direction: rtl; font-family: dejavusans; font-size: 8px; color: #000000; margin: 0; text-align: right; }
        .center { text-align: center; }
        .header { background-color: #000000; color: #ffffff; padding: 9px 7px; }
        .brand { font-size: 14px; font-weight: bold; margin-bottom: 3px; }
        .subtitle { color: #ffffff; font-size: 7px; }
        .muted { color: #4b5563; font-size: 7px; }
        .rule { border-top: 1px dashed #000000; margin: 7px 0; }
        .meta { width: 100%; border-collapse: collapse; }
        .meta td { padding: 2px 0; vertical-align: top; }
        .label { color: #4b5563; width: 35%; }
        .value { font-weight: bold; }
        .status { color: #000000; font-weight: bold; }
        .items { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .items th { background-color: #e5e7eb; color: #000000; border-bottom: 1px solid #000000; padding: 4px 2px; font-size: 7px; }
        .items td { border-bottom: 1px dotted #6b7280; padding: 5px 2px; vertical-align: top; }
        .items .number { text-align: left; direction: ltr; }
        .total { width: 100%; border-collapse: collapse; margin-top: 8px; padding: 6px 5px; background-color: #e5e7eb; color: #000000; font-size: 11px; font-weight: bold; }
        .total td { padding: 6px 5px; }
        .total td:last-child { text-align: left; direction: ltr; font-size: 12px; }
        .note { background-color: #f3f4f6; border-right: 2px solid #000000; padding: 5px 6px; margin-top: 7px; font-size: 7px; }
        .footer { margin-top: 10px; color: #000000; font-size: 7px; }
    </style>
</head>
<body>
    <div class="header center">
        <div class="brand">{{ $order->laundry->name }}</div>
        <div class="subtitle">تذكرة استلام الطلب</div>
    </div>

    <div class="rule"></div>

    <table class="meta">
        <tr><td class="label">رقم الطلب</td><td class="value">{{ $order->order_number }}</td></tr>
        <tr><td class="label">العميل</td><td class="value">{{ $order->client->name }}</td></tr>
        <tr><td class="label">الهاتف</td><td class="value" dir="ltr">{{ $order->client->phone ?: '-' }}</td></tr>
        <tr><td class="label">التاريخ</td><td class="value">{{ $order->received_at->format('Y-m-d') }}</td></tr>
        <tr><td class="label">الحالة</td><td class="status">{{ ['received' => 'تم الاستلام', 'cleaning' => 'قيد المعالجة', 'ready' => 'جاهز للاستلام', 'delivered' => 'تم التسليم'][$order->status] ?? $order->status }}</td></tr>
    </table>

    <div class="rule"></div>

    <table class="items">
        <thead><tr><th>القطعة</th><th>الخدمة / المقاس</th><th>العدد</th><th>المجموع</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->pieces_type }}<br><span class="muted">{{ $item->pieces_color ?: '-' }}</span></td>
                    <td>{{ $item->service }}@if($item->dimensions)<br><span class="muted">{{ $item->dimensions }}</span>@endif</td>
                    <td class="number">{{ $item->quantity }}</td>
                    <td class="number">{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total">
        <tr><td>المجموع</td><td>{{ number_format($order->price, 2) }} د.م</td></tr>
    </table>

    <div class="center footer">احتفظ بهذه التذكرة عند استلام الطلب</div>
    @if($order->delivery_required)<div class="note"><strong>التوصيل:</strong> {{ $order->delivery_address ?: 'مطلوب' }}</div>@endif
    @if($order->notes)<div class="note"><strong>ملاحظات:</strong> {{ $order->notes }}</div>@endif
</body>
</html>