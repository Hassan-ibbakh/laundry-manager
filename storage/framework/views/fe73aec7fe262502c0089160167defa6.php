<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'لوحة المغسلة'); ?> — LaundryOS</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-gradient-to-l from-blue-500 to-violet-500 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                
                <?php
                    $laundry = auth('laundry')->user();
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laundry && $laundry->logo_url): ?>
                    <img src="<?php echo e($laundry->logo_url); ?>" 
                         alt="Logo de <?php echo e($laundry->name); ?>" 
                         class="h-10 w-10 object-cover rounded-full border-2 border-white/30 shadow-sm">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <h1 class="text-xl font-bold">
                 <?php echo e($laundry ? $laundry->name : 'LaundryOS'); ?>

                </h1>
            </div>
            <div class="flex items-center gap-4">
                <form method="POST" action="<?php echo e(route('laundry.logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="bg-white text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-gray-100 transition">
                        خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Nav Links -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 flex gap-6 py-2">
            <a href="<?php echo e(route('laundry.dashboard')); ?>"
               class="text-sm font-medium <?php echo e(request()->routeIs('laundry.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600'); ?>">
                الرئيسية
            </a>
            <a href="<?php echo e(route('laundry.orders.index')); ?>"
               class="text-sm font-medium <?php echo e(request()->routeIs('laundry.orders.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600'); ?>">
                الطلبات
            </a>
            <a href="<?php echo e(route('laundry.clients.index')); ?>"
               class="text-sm font-medium <?php echo e(request()->routeIs('laundry.clients.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600'); ?>">
                العملاء
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
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.dataset.submitted === 'true') {
                        event.preventDefault();
                        return;
                    }
                    form.dataset.submitted = 'true';
                    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function(button) {
                        button.disabled = true;
                    });
                });
            });
        });
    </script>
</body>
</html><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/layouts/laundry.blade.php ENDPATH**/ ?>