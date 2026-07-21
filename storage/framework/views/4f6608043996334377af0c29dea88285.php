
<?php $__env->startSection('title', 'طلب جديد'); ?>
<?php $__env->startSection('content'); ?>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6">
        <span class="text-2xl">📋</span>
        <div>
            <h2 class="text-xl font-bold text-gray-800">إنشاء طلب جديد</h2>
            <p class="text-sm text-gray-500">سجل طلب غسيل جديد للعميل</p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="bg-red-50 border-r-4 border-red-500 rounded-lg px-4 py-3 mb-4">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-medium text-red-700 text-sm">يرجى تصحيح الأخطاء التالية :</p>
                    <ul class="mt-1 space-y-0.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li class="text-red-600 text-sm">• <?php echo e($error); ?></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('laundry.orders.store')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        <!-- Client -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">اختر عميلاً موجوداً</label>
            <select name="client_id" id="client_select"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">— عميل جديد —</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                        <?php echo e($client->name); ?> — <?php echo e($client->phone); ?>

                    </option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
        </div>

        <div id="new_client_fields" class="<?php echo e(old('client_id') ? 'hidden' : ''); ?> space-y-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">👤 اسم العميل الجديد</label>
                <input type="text" name="client_name" value="<?php echo e(old('client_name')); ?>"
                    placeholder="أدخل اسم العميل"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📱 هاتف العميل</label>
                <input type="text" name="client_phone" value="<?php echo e(old('client_phone')); ?>"
                    placeholder="06 XX XX XX XX"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
        </div>

        <hr class="my-4 border-gray-200">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> عدد القطع
                </label>
                <input type="number" name="pieces_count" value="<?php echo e(old('pieces_count', 1)); ?>" min="1" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> نوع القطع
                </label>
                <input type="text" name="pieces_type" value="<?php echo e(old('pieces_type')); ?>" required
                    placeholder="مثال: قميص, بنطلون..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🎨 اللون (اختياري)</label>
                <input type="text" name="pieces_color" value="<?php echo e(old('pieces_color')); ?>"
                    placeholder="مثال: أبيض, أزرق..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> الخدمة
                </label>
                <select name="service" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">اختر الخدمة</option>
                    <option value="غسيل" <?php echo e(old('service') == 'غسيل' ? 'selected' : ''); ?>>🧺 غسيل</option>
                    <option value="كي" <?php echo e(old('service') == 'كي' ? 'selected' : ''); ?>>👔 كي</option>
                    <option value="غسيل+كي" <?php echo e(old('service') == 'غسيل+كي' ? 'selected' : ''); ?>>🧺 غسيل + كي</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> المبلغ (درهم)
                </label>
                <input type="number" name="price" value="<?php echo e(old('price')); ?>" step="0.01" min="0" required
                    placeholder="0.00"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> تاريخ الاستلام
                </label>
                <input type="date" name="received_at" value="<?php echo e(old('received_at', date('Y-m-d'))); ?>" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
        </div>

        <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
            <button type="submit"
                class="bg-gradient-to-l from-blue-600 to-blue-700 text-white px-8 py-2.5 rounded-lg hover:shadow-lg transition font-semibold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                إنشاء الطلب
            </button>
            <a href="<?php echo e(route('laundry.orders.index')); ?>"
               class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('client_select');
    const newClientFields = document.getElementById('new_client_fields');

    if (clientSelect) {
        clientSelect.addEventListener('change', function() {
            if (this.value === '') {
                newClientFields.classList.remove('hidden');
            } else {
                newClientFields.classList.add('hidden');
            }
        });
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.laundry', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/orders/create.blade.php ENDPATH**/ ?>