<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }
        body {
            direction: rtl;
            font-family: dejavusans;
            color: #243447;
            font-size: 10px;
            margin: 0;
        }
        .header { background-color: #173f5f; color: #ffffff; padding: 18px 20px; }
        .brand { width: 65%; }
        .order-meta { width: 35%; text-align: left; }
        h1 { color: #ffffff; font-size: 21px; margin: 0 0 6px; }
        h2 { color: #173f5f; font-size: 13px; margin: 20px 0 8px; }
        .muted { color: #d9e7f2; font-size: 9px; }
        .order-number { color: #ffffff; font-size: 15px; font-weight: bold; }
        .summary { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .summary td { padding: 8px 10px; border: 1px solid #d9e2ec; }
        .summary .label { background-color: #f1f5f9; color: #5b6b7a; font-weight: bold; width: 18%; }
        .summary .value { width: 32%; }
        .status { color: #0f766e; font-weight: bold; }
        .items { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .items th { background-color: #e7f0f7; color: #173f5f; text-align: right; font-weight: bold; }
        .items th, .items td { border: 1px solid #cbd8e3; padding: 8px 6px; }
        .items tr:nth-child(even) td { background-color: #f8fafc; }
        .notes { background-color: #fff8e7; border-right: 3px solid #e0a52b; padding: 9px 11px; margin-top: 16px; }
        .total-box { background-color: #e7f0f7; border: 1px solid #b7cce0; padding: 12px 14px; margin-top: 18px; }
        .total-label { color: #526575; font-size: 11px; }
        .total { color: #173f5f; font-size: 17px; font-weight: bold; text-align: left; }
        .footer { color: #8493a1; font-size: 8px; text-align: center; margin-top: 24px; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="brand">
                <h1>{{ $order->laundry->name }}</h1>
                <div class="muted">فاتورة وتفاصيل الطلب</div>
            </td>
            <td class="order-meta">
                <div class="muted">رقم الطلب</div>
                <div class="order-number">{{ $order->order_number }}</div>
            </td>
        </tr>
    </table>

    @php
        $statusLabels = [
            'received' => 'تم الاستلام',
            'cleaning' => 'قيد المعالجة',
            'ready' => 'جاهز للاستلام',
            'delivered' => 'تم التسليم',
        ];
    @endphp

    <table class="summary">
        <tr>
            <td class="label">العميل</td><td class="value">{{ $order->client->name }}</td>
            <td class="label">الهاتف</td><td class="value">{{ $order->client->phone }}</td>
        </tr>
        <tr>
            <td class="label">الحالة</td><td class="value status">{{ $statusLabels[$order->status] ?? $order->status }}</td>
            <td class="label">تاريخ الاستلام</td><td class="value">{{ $order->received_at->format('Y-m-d') }}</td>
        </tr>
    </table>

    <h2>تفاصيل القطع</h2>
    <table class="items">
        <thead>
            <tr><th>النوع</th><th>اللون</th><th>الخدمة</th><th>الكمية</th><th>السعر</th><th>المجموع</th></tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->pieces_type }}</td>
                    <td>{{ $item->pieces_color ?: '-' }}</td>
                    <td>{{ $item->service }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }} د.م</td>
                    <td>{{ number_format($item->total_price, 2) }} د.م</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($order->notes)
        <div class="notes"><strong>ملاحظات:</strong> {{ $order->notes }}</div>
    @endif

    <div class="total-box">
        <span class="total-label">المبلغ الإجمالي</span>
        <span class="total">{{ number_format($order->price, 2) }} د.م</span>
    </div>

    <div class="footer">شكراً لاختياركم خدمتنا</div>
</body>
</html>