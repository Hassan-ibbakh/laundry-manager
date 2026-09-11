<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $laundryId = auth('laundry')->id();

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->date)->toDateString()
            : Carbon::today()->toDateString();

        $dailyOrders = Order::where('laundry_id', $laundryId)
            ->whereDate('received_at', $selectedDate)
            ->with(['client', 'items'])
            ->latest()
            ->get();

        $dailyCount = $dailyOrders->count();
        $totalRevenue = $dailyOrders->sum('price');
        $paidRevenue = $dailyOrders->where('payment_status', 'paid')->sum('price');
        $unpaidRevenue = $totalRevenue - $paidRevenue;
        $totalPieces = $dailyOrders->sum(fn (Order $order) => $order->items->sum('quantity'));

        $statuses = [
            'received' => ['label' => 'تم الاستلام', 'count' => $dailyOrders->where('status', 'received')->count(), 'color' => 'bg-amber-500'],
            'cleaning' => ['label' => 'قيد الغسيل', 'count' => $dailyOrders->where('status', 'cleaning')->count(), 'color' => 'bg-blue-500'],
            'ready' => ['label' => 'جاهز للاستلام', 'count' => $dailyOrders->where('status', 'ready')->count(), 'color' => 'bg-emerald-500'],
            'delivered' => ['label' => 'تم التسليم', 'count' => $dailyOrders->where('status', 'delivered')->count(), 'color' => 'bg-slate-500'],
        ];

        foreach ($statuses as $key => $status) {
            $statuses[$key]['percentage'] = $dailyCount ? (int) round($status['count'] * 100 / $dailyCount) : 0;
        }

        $recentClients = Client::where('laundry_id', $laundryId)
            ->latest()
            ->take(5)
            ->get();

        $globalStats = [
            'all_orders' => Order::where('laundry_id', $laundryId)->count(),
            'all_clients' => Client::where('laundry_id', $laundryId)->count(),
        ];

        return view('laundry.dashboard', compact(
            'selectedDate',
            'dailyOrders',
            'dailyCount',
            'totalRevenue',
            'paidRevenue',
            'unpaidRevenue',
            'totalPieces',
            'statuses',
            'recentClients',
            'globalStats',
        ));
    }
}
