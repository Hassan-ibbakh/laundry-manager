<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; font-size: 13px; color: #333; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        td:first-child { color: #888; width: 40%; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; background: #dbeafe; color: #1d4ed8; }
        .total { font-size: 18px; font-weight: bold; color: #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0">🧺 {{ $order->laundry->name }}</h2>
        <p style="margin:4px 0 0">وصل استلام ملابس</p>
    </div>

    <table>
        <tr><td>رقم الطلب</td><td><strong>{{ $order->order_number }}</strong></td></tr>
        <tr><td>اسم العميل</td><td>{{ $order->client->name }}</td></tr>
        <tr><td>هاتف العميل</td><td>{{ $order->client->phone }}</td></tr>
        <tr><td>الخدمة</td><td>{{ $order->service }}</td></tr>
        <tr><td>عدد القطع</td><td>{{ $order->pieces_count }}</td></tr>
        <tr><td>نوع القطع</td><td>{{ $order->pieces_type }}</td></tr>
        <tr><td>اللون</td><td>{{ $order->pieces_color ?? '—' }}</td></tr>
        <tr><td>تاريخ الاستلام</td><td>{{ $order->received_at }}</td></tr>
        <tr><td>الحالة</td><td><span class="status">{{ $order->status }}</span></td></tr>
        <tr><td>المبلغ الإجمالي</td><td class="total">{{ $order->price }} درهم</td></tr>
    </table>

    <p style="text-align:center; color:#aaa; margin-top:30px; font-size:11px;">
        LaundryOS — شكراً لثقتكم
    </p>
</body>
</html>