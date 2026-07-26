
<?php $__env->startSection('title', 'الطلبات'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">📦 الطلبات</h2>
        <p class="text-sm text-gray-500 mt-1">قائمة جميع الطلبات</p>
    </div>
    <a href="<?php echo e(route('laundry.orders.create')); ?>"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1">
        <span>+</span> طلب جديد
    </a>
</div>

<!-- Filters -->
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-end gap-3">
    <div class="flex-1 min-w-[150px]">
        <label class="block text-xs text-gray-500 mb-1">بحث</label>
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
            placeholder="رقم الطلب أو اسم العميل"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="w-40">
        <label class="block text-xs text-gray-500 mb-1">الحالة</label>
        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">الكل</option>
            <option value="received"  <?php echo e(request('status') == 'received'  ? 'selected' : ''); ?>>📥 تم الاستلام</option>
            <option value="cleaning"  <?php echo e(request('status') == 'cleaning'  ? 'selected' : ''); ?>>🧺 قيد الغسيل</option>
            <option value="ready"     <?php echo e(request('status') == 'ready'     ? 'selected' : ''); ?>>✅ جاهز</option>
            <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>>📦 تم التسليم</option>
        </select>
    </div>

    <div class="w-48">
        <label class="block text-xs text-gray-500 mb-1">تاريخ الطلب</label>
        <input type="date" name="date" value="<?php echo e(request('date')); ?>"
               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition">
            🔍 بحث
        </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search', 'status', 'date'])): ?>
            <a href="<?php echo e(route('laundry.orders.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
                إعادة تعيين
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
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
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs font-bold text-gray-700"><?php echo e($order->order_number); ?></td>
                    <td class="px-4 py-3"><?php echo e($order->client->name ?? '—'); ?></td>
                    <td class="px-4 py-3"><?php echo e($order->service); ?></td>
                    <td class="px-4 py-3 font-semibold"><?php echo e(number_format($order->price, 2)); ?> د.م</td>
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
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($colors[$order->status] ?? 'bg-gray-100 text-gray-700'); ?>">
                            <?php echo e($labels[$order->status] ?? $order->status); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?php echo e($order->created_at->format('d/m/Y')); ?>

                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('laundry.orders.show', $order->id)); ?>"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">
                                👁️ عرض
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="<?php echo e(route('laundry.orders.whatsapp', $order->id)); ?>"
                               class="text-green-600 hover:text-green-800 text-xs font-medium transition" target="_blank">
                                💬
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="<?php echo e(route('laundry.orders.pdf', $order->id)); ?>"
                               class="text-red-600 hover:text-red-800 text-xs font-medium transition" target="_blank">
                                📄
                            </a>
                        </div>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">📦</div>
                        <p class="text-lg font-medium">لا توجد طلبات</p>
                        <p class="text-sm mt-1">قم بإنشاء أول طلب لك</p>
                        <a href="<?php echo e(route('laundry.orders.create')); ?>" class="text-blue-600 hover:underline text-sm mt-3 inline-block">
                            إنشاء أول طلب →
                        </a>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders->hasPages()): ?>
    <div class="px-6 py-4 border-t bg-gray-50/50">
        <?php echo e($orders->links()); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Total -->
    <div class="px-6 py-3 text-xs text-gray-400 border-t flex justify-between items-center">
        <span>إجمالي : <?php echo e($orders->total()); ?> طلب</span>
        <span>الصفحة <?php echo e($orders->currentPage()); ?> من <?php echo e($orders->lastPage()); ?></span>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.laundry', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/orders/index.blade.php ENDPATH**/ ?>