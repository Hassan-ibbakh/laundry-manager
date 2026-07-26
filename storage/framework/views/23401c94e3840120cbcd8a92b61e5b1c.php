
<?php $__env->startSection('title', 'لوحة المغسلة'); ?>
<?php $__env->startSection('content'); ?>


<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        
        <?php
            $laundry = auth('laundry')->user();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundry->logo_url): ?>
            <img src="<?php echo e($laundry->logo_url); ?>" 
                 alt="Logo de <?php echo e($laundry->name); ?>" 
                 class="h-14 w-14 object-cover rounded-xl border-2 border-gray-200 shadow-sm">
        <?php else: ?>
            <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                <?php echo e(strtoupper(substr($laundry->name, 0, 2))); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo e($laundry->name); ?></h2>
            <p class="text-sm text-gray-500">📊 لوحة التحكم</p>
        </div>
    </div>
    <div class="text-sm text-gray-400">
        <?php echo e(now()->format('d/m/Y H:i')); ?>

    </div>
</div>


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


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
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
        <div class="px-6 py-3 border-t text-xs text-gray-400">
            عرض آخر <?php echo e($orders->count()); ?>

        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b">
            <h3 class="font-semibold text-gray-700">👤 العملاء الجدد</h3>
            <p class="text-xs text-gray-400">5 derniers clients</p>
        </div>
        <div class="p-4 space-y-3 max-h-80 overflow-y-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?php echo e($client->name); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($client->phone); ?></p>
                    </div>
                    <span class="text-xs text-gray-400"><?php echo e($client->created_at->diffForHumans()); ?></span>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="text-center py-6 text-gray-400">
                    <div class="text-3xl mb-2">👤</div>
                    <p class="text-sm">لا يوجد عملاء بعد</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentClients->count() > 0): ?>
            <div class="px-5 py-2 border-t">
                <a href="<?php echo e(route('laundry.clients.index')); ?>" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                    عرض جميع العملاء →
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.laundry', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/dashboard.blade.php ENDPATH**/ ?>