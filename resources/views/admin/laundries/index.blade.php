@extends('layouts.admin')
@section('title', 'إدارة المغاسل')
@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- En-tête -->
    <div class="px-6 py-4 border-b flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">🏪 إدارة المغاسل</h2>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع المغاسل المسجلة</p>
        </div>
        <a href="{{ route('admin.laundries.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            + إضافة مغسلة
        </a>
    </div>

    <!-- Liste des blanchisseries -->
    @if($laundries->isEmpty())
        <div class="text-center py-16">
            <div class="text-7xl mb-4">🏪</div>
            <p class="text-gray-500 text-lg font-medium">لا توجد مغاسل مسجلة</p>
            <p class="text-gray-400 text-sm mt-1">ابدأ بإنشاء أول مغسلة</p>
            <a href="{{ route('admin.laundries.create') }}" 
               class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                إنشاء أول مغسلة →
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b">
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الهاتف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laundries as $laundry)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($laundry->logo)
                                    <img src="{{ $laundry->logo_url }}" alt="{{ $laundry->name }}" class="w-9 h-9 object-cover rounded-full border border-gray-200">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                        {{ strtoupper(substr($laundry->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="font-medium text-gray-800">{{ $laundry->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $laundry->email }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $laundry->phone }}</td>
                        <td class="px-6 py-4">
                            @if($laundry->is_active)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.laundries.edit', $laundry->id) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                    ✏️ تعديل
                                </a>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="{{ route('admin.laundries.destroy', $laundry->id) }}"
                                      onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذه المغسلة؟')" 
                                      class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                        🗑️ حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($laundries->hasPages())
        <div class="px-6 py-4 border-t bg-gray-50/50">
            {{ $laundries->links() }}
        </div>
        @endif
        
        <!-- Total -->
        <div class="px-6 py-3 text-xs text-gray-400 border-t">
            الإجمالي: {{ $laundries->total() }} مغسلة
        </div>
    @endif
</div>

@endsection