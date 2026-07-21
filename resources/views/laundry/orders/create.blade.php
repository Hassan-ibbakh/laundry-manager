@extends('layouts.laundry')
@section('title', 'طلب جديد')
@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6">
        <span class="text-2xl">📋</span>
        <div>
            <h2 class="text-xl font-bold text-gray-800">إنشاء طلب جديد</h2>
            <p class="text-sm text-gray-500">سجل طلب غسيل جديد للعميل</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-r-4 border-red-500 rounded-lg px-4 py-3 mb-4">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-medium text-red-700 text-sm">يرجى تصحيح الأخطاء التالية :</p>
                    <ul class="mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li class="text-red-600 text-sm">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('laundry.orders.store') }}" class="space-y-4">
        @csrf

        <!-- Client -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">اختر عميلاً موجوداً</label>
            <select name="client_id" id="client_select"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">— عميل جديد —</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} — {{ $client->phone }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="new_client_fields" class="{{ old('client_id') ? 'hidden' : '' }} space-y-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">👤 اسم العميل الجديد</label>
                <input type="text" name="client_name" value="{{ old('client_name') }}"
                    placeholder="أدخل اسم العميل"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📱 هاتف العميل</label>
                <input type="text" name="client_phone" value="{{ old('client_phone') }}"
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
                <input type="number" name="pieces_count" value="{{ old('pieces_count', 1) }}" min="1" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> نوع القطع
                </label>
                <input type="text" name="pieces_type" value="{{ old('pieces_type') }}" required
                    placeholder="مثال: قميص, بنطلون..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🎨 اللون (اختياري)</label>
                <input type="text" name="pieces_color" value="{{ old('pieces_color') }}"
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
                    <option value="غسيل" {{ old('service') == 'غسيل' ? 'selected' : '' }}>🧺 غسيل</option>
                    <option value="كي" {{ old('service') == 'كي' ? 'selected' : '' }}>👔 كي</option>
                    <option value="غسيل+كي" {{ old('service') == 'غسيل+كي' ? 'selected' : '' }}>🧺 غسيل + كي</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> المبلغ (درهم)
                </label>
                <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                    placeholder="0.00"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="text-red-500">*</span> تاريخ الاستلام
                </label>
                <input type="date" name="received_at" value="{{ old('received_at', date('Y-m-d')) }}" required
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
            <a href="{{ route('laundry.orders.index') }}"
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

@endsection