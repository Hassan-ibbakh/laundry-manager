<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تسجيل دخول المغسلة - LaundryOS</title>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <style>
        :root {
            /* Échelle fluide : mobile (360px) → TV / grand écran (3840px) */
            --card-max-width: clamp(22rem, 30vw, 34rem);
            --brand-icon: clamp(2.5rem, 3vw, 4rem);
            --title-size: clamp(1.5rem, 2vw, 2.5rem);
            --body-size: clamp(0.875rem, 1vw, 1.15rem);
            --card-padding: clamp(1.5rem, 2.5vw, 3rem);
            --input-padding-y: clamp(0.65rem, 1vw, 1.1rem);
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        /* position: fixed + inset: 0 colle l'arrière-plan à la fenêtre RÉELLEMENT
           visible, ce qui évite les bugs de calcul de 100vh/100dvh dans certains
           navigateurs et webviews mobiles (contenu poussé en haut avec du vide
           en dessous). Le scroll interne prend le relais si le contenu est plus
           grand que l'écran (petits mobiles en paysage, zoom texte, etc.). */
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            inset: 0;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(1rem, 3vw, 3rem);
        }

        .login-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-card {
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease;
            width: 100%;
            max-width: var(--card-max-width);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-icon-wrap {
            width: var(--brand-icon);
            height: var(--brand-icon);
        }

        .brand-icon-wrap svg {
            width: 100%;
            height: 100%;
        }

        .brand-title {
            font-size: var(--title-size);
        }

        .brand-subtitle,
        .helper-text {
            font-size: var(--body-size);
        }

        .login-panel {
            padding: var(--card-padding);
            border-radius: clamp(1rem, 1.5vw, 1.5rem);
        }

        .card-title {
            font-size: clamp(1.25rem, 1.6vw, 1.875rem);
        }

        .card-subtitle {
            font-size: var(--body-size);
        }

        .input-group {
            transition: all 0.3s ease;
            position: relative;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .input-group label {
            font-size: clamp(0.8rem, 0.95vw, 1rem);
        }

        .input-group input {
            transition: all 0.3s ease;
            padding-right: 2.75rem;
            padding-top: var(--input-padding-y);
            padding-bottom: var(--input-padding-y);
            font-size: clamp(0.9rem, 1vw, 1.1rem);
        }

        .input-group .icon {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            width: clamp(1.1rem, 1.2vw, 1.4rem);
            height: clamp(1.1rem, 1.2vw, 1.4rem);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            padding-top: var(--input-padding-y);
            padding-bottom: var(--input-padding-y);
            font-size: clamp(1rem, 1.1vw, 1.3rem);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(102, 126, 234, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Petits mobiles */
        @media (max-width: 360px) {
            .login-panel { padding: 1.25rem; }
        }

        /* Téléviseurs / très grands écrans : on plafonne la largeur et on agrandit
           légèrement le texte pour la distance de lecture (affichage mural, etc.) */
        @media (min-width: 1600px) {
            :root { --card-max-width: 30rem; }
            .login-bg { padding: 4rem; }
        }

        @media (min-width: 2560px) {
            :root {
                --card-max-width: 34rem;
                --title-size: 2.75rem;
                --body-size: 1.25rem;
                --card-padding: 3.5rem;
                --input-padding-y: 1.25rem;
            }
            .card-title { font-size: 2.25rem; }
            .btn-login { font-size: 1.4rem; }
        }

        /* Mode paysage bas (tablette/mobile à l'horizontale) */
        @media (max-height: 480px) and (orientation: landscape) {
            .login-bg { padding: 1rem; align-items: flex-start; }
            .brand-block { margin-bottom: 0.75rem !important; }
            .login-panel { padding: 1.25rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-bg::before,
            .login-card,
            .btn-login {
                animation: none;
                transition: none;
            }
        }
    </style>
</head>
<body class="min-h-screen login-bg">
    <div class="login-card">
        <!-- Logo -->
        <div class="brand-block text-center mb-8">
            <div class="brand-icon-wrap inline-flex items-center justify-center bg-white/20 backdrop-blur-sm rounded-full p-4 mb-3">
                <svg class="text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="brand-title font-bold text-white">LaundryOS</h1>
            <p class="brand-subtitle text-white/70 mt-1">نظام إدارة المغاسل</p>
        </div>

        <!-- Login Card -->
        <div class="login-panel bg-white rounded-2xl shadow-2xl">
            <h2 class="card-title font-bold text-center text-gray-800 mb-2">🔐 تسجيل الدخول</h2>
            <p class="card-subtitle text-center text-gray-500 mb-6">دخول لوحة إدارة المغسلة</p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="bg-red-50 border-r-4 border-red-500 rounded-lg px-4 py-3 mb-4">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="helper-text font-medium"><?php echo e($errors->first()); ?></span>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('laundry.login.post')); ?>">
                <?php echo csrf_field(); ?>
                <div class="input-group mb-4">
                    <label class="block font-medium text-gray-700 mb-1">📧 البريد الإلكتروني</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus
                        placeholder="example@email.com"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>

                <div class="input-group mb-6">
                    <label class="block font-medium text-gray-700 mb-1">🔑 كلمة المرور</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <label class="mb-6 flex items-center gap-2 helper-text text-gray-600">
                    <input type="checkbox" name="remember" value="1"
                        <?php echo e(old('remember') ? 'checked' : ''); ?>

                        class="h-4 w-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                    تذكرني
                </label>

                <button type="submit" class="btn-login w-full text-white rounded-xl font-semibold">
                    🚀 دخول
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="helper-text text-gray-400" style="font-size: clamp(0.65rem, 0.75vw, 0.85rem);">
                    Version 2.0 • Sécurisé par Laravel
                </p>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\ATLAS PRO ELECTRO\Desktop\hass\nadef org\laundry-manager\resources\views/laundry/login.blade.php ENDPATH**/ ?>