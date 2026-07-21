<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'لوحة الإدارة'); ?> — LaundryOS</title>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-gradient-to-l from-blue-600 to-violet-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">🧺 LaundryOS — الإدارة</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm"><?php echo e(auth('admin')->user()->name ?? 'Admin'); ?></span>
                <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-white text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-gray-100 transition-colors">
                        خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Nav Links -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 flex gap-6 py-2">
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="text-sm font-medium <?php echo e(request()->routeIs('admin.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600'); ?>">
                الرئيسية
            </a>
            <a href="<?php echo e(route('admin.laundries.index')); ?>"
               class="text-sm font-medium <?php echo e(request()->routeIs('admin.laundries.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600'); ?>">
                المغاسل
            </a>
        </div>
    </div>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-green-100 text-green-800 border border-green-300 rounded px-4 py-3 mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="bg-red-100 text-red-800 border border-red-300 rounded px-4 py-3 mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <?php echo $__env->yieldContent('content'); ?>
    </main>

</body>
</html><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/layouts/admin.blade.php ENDPATH**/ ?>