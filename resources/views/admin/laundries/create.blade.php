@extends('layouts.admin')
@section('title', 'مغسلة جديدة - LaundryOS')
@section('content')

<style>
    .form-card {
        background: #ffffff;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .form-group {
        position: relative;
        transition: all 0.3s ease;
    }
    .form-group:focus-within {
        transform: translateY(-2px);
    }
    .form-group label {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .form-group input {
        width: 100%;
        border: 2px solid #E5E7EB;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        background: white;
    }
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    .form-group input.error {
        border-color: #EF4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }
    .form-group input[type="file"] {
        padding: 0.5rem;
    }
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -10px rgba(102, 126, 234, 0.5);
    }
    .btn-submit:active {
        transform: translateY(0);
    }
    .btn-cancel {
        transition: all 0.3s ease;
        background: #F3F4F6;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    .btn-cancel:hover {
        background: #E5E7EB;
        transform: translateY(-2px);
    }
    .alert-error {
        border-right: 4px solid #EF4444;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<div class="max-w-2xl mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <a href="{{ route('admin.laundries.index') }}" class="hover:text-blue-600 transition-colors">
                لوحة التحكم
            </a>
            <span>/</span>
            <span>مغسلة جديدة</span>
        </div>
        <div class="flex items-start justify-between mt-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    <span>🏪</span>
                    إضافة مغسلة
                </h2>
                <p class="text-gray-500 mt-1">أنشئ حساب مغسلة جديداً على المنصة</p>
            </div>
            <div class="bg-blue-50 rounded-lg px-4 py-2 text-blue-700 text-sm font-medium flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                نموذج آمن
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card p-8">
        @if($errors->any())
            <div class="alert-error bg-red-50 rounded-xl px-4 py-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-red-700">يرجى تصحيح الأخطاء التالية:</p>
                        <ul class="mt-2 space-y-1 text-sm text-red-600">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.laundries.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="form-group md:col-span-2">
                    <label>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        اسم المغسلة
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="مثال: مغسلة المركز" 
                        class="{{ $errors->has('name') ? 'error' : '' }}">
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        البريد الإلكتروني
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="contact@maghsala.com"
                        class="{{ $errors->has('email') ? 'error' : '' }}">
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        الهاتف
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        placeholder="+212 6XX-XXXXXX"
                        class="{{ $errors->has('phone') ? 'error' : '' }}">
                </div>

                <!-- Logo -->
                <div class="form-group md:col-span-2">
                    <label>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        الشعار (اختياري)
                    </label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">الصيغ المقبولة: JPG، PNG، GIF – الحد الأقصى 2 ميغابايت</p>
                </div>

                <!-- Password -->
                <div class="form-group md:col-span-2">
                    <label>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        كلمة المرور
                    </label>
                    <input type="password" name="password" required
                        placeholder="الحد الأدنى 6 أحرف"
                        class="{{ $errors->has('password') ? 'error' : '' }}">
                    <p class="text-xs text-gray-400 mt-1">يجب أن تحتوي كلمة المرور على 6 أحرف على الأقل</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-submit text-white px-8 py-3 rounded-xl font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    إنشاء المغسلة
                </button>
                <a href="{{ route('admin.laundries.index') }}" 
                   class="btn-cancel px-6 py-3 rounded-xl text-gray-600 font-medium">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-blue-50/50 rounded-xl p-4 border border-blue-100">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-gray-700">معلومات مهمة</p>
                <ul class="mt-1 text-xs text-gray-500 space-y-1">
                    <li>• ستتلقى المغسلة رسالة تأكيد عبر البريد الإلكتروني</li>
                    <li>• يجب أن تكون كلمة المرور آمنة (6 أحرف على الأقل)</li>
                    <li>• يمكنك تفعيل/تعطيل الحساب لاحقاً</li>
                    <li>• الشعار اختياري لكنه موصى به لهوية العلامة التجارية</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection