
<?php $__env->startSection('title', 'لوحة الإدارة - LaundryOS'); ?>
<?php $__env->startSection('content'); ?>

<style>
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .stat-card .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .stat-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(-5deg);
    }
    .stat-number {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 2.5rem;
        font-weight: 800;
    }
    .table-row {
        transition: all 0.2s ease;
    }
    .table-row:hover {
        background: linear-gradient(90deg, #f0f4ff 0%, #ffffff 100%);
        transform: scale(1.01);
    }
    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .badge-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-active::before {
        background: #10b981;
        animation: pulse-dot 2s infinite;
    }
    .badge-inactive::before {
        background: #ef4444;
    }
    @keyframes pulse-dot {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
    .action-btn {
        transition: all 0.2s ease;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <span>📊</span>
                لوحة التحكم
            </h2>
            <p class="text-gray-500 mt-1">نظرة عامة على منصة إدارة المغاسل الخاصة بك</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-400">آخر تحديث: <?php echo e(now()->format('d/m/Y H:i')); ?></span>
            <button class="bg-gray-100 hover:bg-gray-200 p-2 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">إجمالي المغاسل</p>
                    <p class="stat-number mt-2"><?php echo e($stats['total_laundries']); ?></p>
                    <p class="text-xs text-green-500 mt-2">
                        <span class="font-bold">+12%</span> هذا الشهر
                    </p>
                </div>
                <div class="icon-wrapper bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">المغاسل النشطة</p>
                    <p class="stat-number mt-2"><?php echo e($stats['active_laundries']); ?></p>
                    <p class="text-xs text-green-500 mt-2">
                        <span class="font-bold"><?php echo e($stats['active_percentage']); ?>%</span> من الإجمالي
                    </p>
                </div>
                <div class="icon-wrapper bg-green-50 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">إجمالي الطلبات</p>
                    <p class="stat-number mt-2"><?php echo e($stats['total_orders']); ?></p>
                    <p class="text-xs text-blue-500 mt-2">
                        <span class="font-bold">+5</span> اليوم
                    </p>
                </div>
                <div class="icon-wrapper bg-violet-50 text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-800 text-lg">إدارة المغاسل</h3>
                <p class="text-sm text-gray-400 mt-1">قائمة جميع المغاسل المسجلة</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="بحث..." 
                        class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <a href="<?php echo e(route('admin.laundries.create')); ?>"
                   class="bg-gradient-to-r from-blue-600 to-violet-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all hover:scale-105">
                    + مغسلة جديدة
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المغسلة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الهاتف</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $laundries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laundry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="table-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-violet-500 flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo e(strtoupper(substr($laundry->name, 0, 2))); ?>

                                </div>
                                <span class="font-medium text-gray-800"><?php echo e($laundry->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm"><?php echo e($laundry->email); ?></td>
                        <td class="px-6 py-4 text-gray-600 text-sm"><?php echo e($laundry->phone); ?></td>
                        <td class="px-6 py-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundry->is_active): ?>
                                <span class="badge-status badge-active bg-green-50 text-green-700">
                                    نشط
                                </span>
                            <?php else: ?>
                                <span class="badge-status badge-inactive bg-red-50 text-red-700">
                                    غير نشط
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.laundries.edit', $laundry->id)); ?>"
                                   class="action-btn bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    تعديل
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.laundries.destroy', $laundry->id)); ?>"
                                      onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذه المغسلة؟')" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="action-btn bg-red-50 text-red-600 hover:bg-red-100">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-400">لا توجد مغاسل مسجلة</p>
                                <a href="<?php echo e(route('admin.laundries.create')); ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                    أنشئ أول مغسلة لك →
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundries->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-100">
            <?php echo e($laundries->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>