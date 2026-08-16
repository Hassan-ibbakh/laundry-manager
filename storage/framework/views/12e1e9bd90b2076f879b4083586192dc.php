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
        <h2 style="margin:0">🧺 <?php echo e($order->laundry->name); ?></h2>
        <p style="margin:4px 0 0">وصل استلام ملابس</p>
    </div>

    <table>
        <tr><td>رقم الطلب</td><td><strong><?php echo e($order->order_number); ?></strong></td></tr>
        <tr><td>اسم العميل</td><td><?php echo e($order->client->name); ?></td></tr>
        <tr><td>هاتف العميل</td><td><?php echo e($order->client->phone); ?></td></tr>
        <tr><td>الخدمة</td><td><?php echo e($order->service); ?></td></tr>
        <tr><td>القطع</td>
            <td>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div><?php echo e($item->pieces_type); ?> <?php echo e($item->pieces_color ? '('.$item->pieces_color.')' : ''); ?> (<?php echo e($item->quantity); ?> × <?php echo e(number_format($item->unit_price, 2)); ?>DH)</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    —
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
        </tr>
        <tr><td>تاريخ الاستلام</td><td><?php echo e($order->received_at); ?></td></tr>
        <tr><td>الحالة</td><td><span class="status"><?php echo e($order->status); ?></span></td></tr>
        <tr><td>المبلغ الإجمالي</td><td class="total"><?php echo e(number_format($order->price, 2)); ?> درهم</td></tr>
    </table>

    <p style="text-align:center; color:#aaa; margin-top:30px; font-size:11px;">
        LaundryOS — شكراً لثقتكم
    </p>
</body>
</html><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/pdf/order.blade.php ENDPATH**/ ?>