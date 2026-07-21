
<?php $__env->startSection('title', 'العملاء'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">العملاء</h2>
    <a href="<?php echo e(route('laundry.clients.create')); ?>"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        + عميل جديد
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-6 py-3 text-right">#</th>
                <th class="px-6 py-3 text-right">الاسم</th>
                <th class="px-6 py-3 text-right">الهاتف</th>
                <th class="px-6 py-3 text-right">تاريخ الإضافة</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-gray-400"><?php echo e($loop->iteration); ?></td>
                <td class="px-6 py-4 font-medium"><?php echo e($client->name); ?></td>
                <td class="px-6 py-4"><?php echo e($client->phone); ?></td>
                <td class="px-6 py-4 text-gray-400 text-xs"><?php echo e($client->created_at->format('Y/m/d')); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">لا يوجد عملاء بعد</td>
            </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
    <div class="px-6 py-3"><?php echo e($clients->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.laundry', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/clients/index.blade.php ENDPATH**/ ?>