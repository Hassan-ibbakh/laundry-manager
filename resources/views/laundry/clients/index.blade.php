@extends('layouts.laundry')
@section('title', 'العملاء')
@section('content')

<div class="flex flex-wrap gap-3 justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">العملاء</h2>
    <a href="{{ route('laundry.clients.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        + عميل جديد
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[36rem] text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-6 py-3 text-right">#</th>
                <th class="px-6 py-3 text-right">الاسم</th>
                <th class="px-6 py-3 text-right">الهاتف</th>
                <th class="px-6 py-3 text-right">تاريخ الإضافة</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($clients as $client)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 font-medium">{{ $client->name }}</td>
                <td class="px-6 py-4">{{ $client->phone }}</td>
                <td class="px-6 py-4 text-gray-400 text-xs">{{ $client->created_at->format('Y/m/d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">لا يوجد عملاء بعد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-3">{{ $clients->links() }}</div>
</div>

@endsection