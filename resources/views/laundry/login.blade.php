<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول المغسلة - LaundryOS</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
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
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-group {
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            transform: translateY(-2px);
        }
        .input-group input {
            transition: all 0.3s ease;
            padding-right: 2.5rem;
        }
        .input-group .icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(102, 126, 234, 0.5);
        }
        .btn-login:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="min-h-screen login-bg flex items-center justify-center p-4">
    <div class="login-card w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white/20 backdrop-blur-sm rounded-full p-4 mb-3">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white">LaundryOS</h1>
            <p class="text-white/70 text-sm mt-1">نظام إدارة المغاسل</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">🔐 تسجيل الدخول</h2>
            <p class="text-center text-gray-500 text-sm mb-6">دخول لوحة إدارة المغسلة</p>

            @if($errors->any())
                <div class="bg-red-50 border-r-4 border-red-500 rounded-lg px-4 py-3 mb-4">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('laundry.login.post') }}">
                @csrf
                <div class="input-group mb-4 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">📧 البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="example@email.com"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>

                <div class="input-group mb-6 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">🔑 كلمة المرور</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <button type="submit" class="btn-login w-full text-white py-3 rounded-xl font-semibold text-lg">
                    🚀 دخول
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400">
                    Version 2.0 • Sécurisé par Laravel
                </p>
            </div>
        </div>
    </div>
</body>
</html>