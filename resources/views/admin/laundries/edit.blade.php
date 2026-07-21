@extends('layouts.admin')
@section('title', 'تعديل مغسلة')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">تعديل المغسلة</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 rounded px-4 py-3 mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.laundries.update', $laundry->id) }}">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" name="name" value="{{ old('name', $laundry->name) }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email', $laundry->email) }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone', $laundry->phone) }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة (اتركها فارغة إن لم تريد تغييرها)</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="mb-6 flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ $laundry->is_active ? 'checked' : '' }}
                class="w-4 h-4 text-blue-600">
            <label for="is_active" class="text-sm text-gray-700">حساب نشط</label>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                تحديث
            </button>
            <a href="{{ route('admin.laundries.index') }}"
               class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200">
                إلغاء
            </a>
        </div>
    </form>
</div>

@endsection