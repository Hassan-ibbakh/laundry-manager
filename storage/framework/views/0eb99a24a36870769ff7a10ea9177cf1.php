
<?php $__env->startSection('title', 'تفاصيل الطلب'); ?>
<?php $__env->startSection('content'); ?>

<?php
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
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6 mb-4">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800"><?php echo e($order->order_number); ?></h2>
                <p class="text-sm text-gray-400"><?php echo e($order->received_at->format('Y-m-d')); ?></p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm <?php echo e($colors[$order->status]); ?>">
                <?php echo e($labels[$order->status]); ?>

            </span>
        </div>

        
        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div><p class="text-gray-400">العميل</p><p class="font-medium"><?php echo e($order->client->name); ?></p></div>
            <div><p class="text-gray-400">الهاتف</p><p class="font-medium"><?php echo e($order->client->phone); ?></p></div>
            <div><p class="text-gray-400">المبلغ الإجمالي</p><p class="font-bold text-blue-600 text-lg"><?php echo e(number_format($order->price, 2)); ?> د.م</p></div>
        </div>

        
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">القطع</h3>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="flex justify-between items-center border-b border-gray-200 pb-2 last:border-0">
                        <div>
                            <span class="font-medium"><?php echo e($item->pieces_type); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->pieces_color): ?>
                                <span class="text-sm text-gray-500"> - <?php echo e($item->pieces_color); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="text-xs text-gray-400 mx-1">(<?php echo e($item->service); ?>)</span>
                            <span class="text-sm text-gray-500 mx-2">(<?php echo e($item->quantity); ?> × <?php echo e(number_format($item->unit_price, 2)); ?> د.م)</span>
                        </div>
                        <span class="font-bold"><?php echo e(number_format($item->total_price, 2)); ?> د.م</span>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <p class="text-gray-400 text-sm">لا توجد قطع مسجلة</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <form method="POST" action="<?php echo e(route('laundry.orders.status', $order->id)); ?>" class="mb-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <div class="flex gap-3 items-center">
                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="received"  <?php echo e($order->status == 'received'  ? 'selected' : ''); ?>>تم الاستلام</option>
                    <option value="cleaning"  <?php echo e($order->status == 'cleaning'  ? 'selected' : ''); ?>>قيد الغسيل</option>
                    <option value="ready"     <?php echo e($order->status == 'ready'     ? 'selected' : ''); ?>>جاهز للاستلام</option>
                    <option value="delivered" <?php echo e($order->status == 'delivered' ? 'selected' : ''); ?>>تم التسليم</option>
                </select>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    تحديث الحالة
                </button>
            </div>
        </form>

        
        <div class="flex flex-wrap gap-3 pt-4 border-t">
            <a href="<?php echo e(route('laundry.orders.whatsapp', $order->id)); ?>" target="_blank"
               class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm hover:bg-green-200 transition">
                💬 إرسال واتساب
            </a>
            <a href="<?php echo e(route('tracking.show', $order->tracking_token)); ?>" target="_blank"
               class="bg-violet-100 text-violet-700 px-4 py-2 rounded-lg text-sm hover:bg-violet-200 transition">
                🔗 رابط التتبع
            </a>
        </div>
    </div>

    <a href="<?php echo e(route('laundry.orders.index')); ?>" class="text-sm text-gray-500 hover:underline transition">
        ← رجوع إلى القائمة
    </a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.laundry', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/orders/show.blade.php ENDPATH**/ ?>