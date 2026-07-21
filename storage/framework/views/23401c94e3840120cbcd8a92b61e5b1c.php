
<?php $__env->startSection('title', 'لوحة المغسلة'); ?>
<?php $__env->startSection('content'); ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📊 لوحة التحكم</h2>
    <p class="text-sm text-gray-500 mt-1">مرحباً بك في لوحة تحكم المغسلة</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5 border-r-4 border-yellow-400 hover:shadow-md transition">
        <p class="text-xs text-gray-500 mb-1">📥 تم الاستلام</p>
        <p class="text-3xl font-bold text-yellow-500"><?php echo e($stats['received'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-r-4 border-blue-400 hover:shadow-md transition">
        <p class="text-xs text-gray-500 mb-1">🧺 قيد الغسيل</p>
        <p class="text-3xl font-bold text-blue-500"><?php echo e($stats['cleaning'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-r-4 border-green-400 hover:shadow-md transition">
        <p class="text-xs text-gray-500 mb-1">✅ جاهز للاستلام</p>
        <p class="text-3xl font-bold text-green-500"><?php echo e($stats['ready'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-r-4 border-gray-400 hover:shadow-md transition">
        <p class="text-xs text-gray-500 mb-1">📦 تم التسليم</p>
        <p class="text-3xl font-bold text-gray-500"><?php echo e($stats['delivered'] ?? 0); ?></p>
    </div>
</div>

<!-- Second row stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-purple-400">
        <p class="text-xs text-gray-500 mb-1">📋 إجمالي الطلبات</p>
        <p class="text-2xl font-bold text-purple-500"><?php echo e($stats['total'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-pink-400">
        <p class="text-xs text-gray-500 mb-1">👥 إجمالي العملاء</p>
        <p class="text-2xl font-bold text-pink-500"><?php echo e($stats['total_clients'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-orange-400">
        <p class="text-xs text-gray-500 mb-1">📅 طلبات اليوم</p>
        <p class="text-2xl font-bold text-orange-500"><?php echo e($stats['today_orders'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-indigo-400">
        <p class="text-xs text-gray-500 mb-1">⏳ قيد المعالجة</p>
        <p class="text-2xl font-bold text-indigo-500"><?php echo e($stats['pending_orders'] ?? 0); ?></p>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex flex-wrap justify-between items-center gap-2">
        <div>
            <h3 class="font-semibold text-gray-700">📋 آخر الطلبات</h3>
            <p class="text-xs text-gray-400">أحدث 10 طلبات</p>
        </div>
        <a href="<?php echo e(route('laundry.orders.create')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-1">
            <span>+</span> طلب جديد
        </a>
    </div>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-gray-700"><?php echo e($order->order_number); ?></td>
                    <td class="px-4 py-3"><?php echo e($order->client->name ?? '—'); ?></td>
                    <td class="px-4 py-3"><?php echo e($order->service); ?></td>
                    <td class="px-4 py-3 font-semibold"><?php echo e($order->price); ?> د.م</td>
                    <td class="px-4 py-3">
                        <?php
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
                        ?>
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($colors[$order->status]); ?>">
                            <?php echo e($labels[$order->status]); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('laundry.orders.show', $order->id)); ?>"
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">
                            عرض
                        </a>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                        <div class="text-4xl mb-2">📋</div>
                        <p>لا توجد طلبات بعد</p>
                        <a href="<?php echo e(route('laundry.orders.create')); ?>" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                            إنشاء أول طلب →
                        </a>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.laundry', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/dashboard.blade.php ENDPATH**/ ?>