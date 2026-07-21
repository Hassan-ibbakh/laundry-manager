@extends('layouts.laundry')
@section('title', 'إضافة عميل')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">إضافة عميل جديد</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 rounded px-4 py-3 mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('laundry.clients.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                حفظ
            </button>
            <a href="{{ route('laundry.clients.index') }}"
               class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200">
                إلغاء
            </a>
        </div>
    </form>
</div>

@endsection