<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $laundryId = auth('laundry')->id();

        $stats = [
            'received'  => Order::where('laundry_id', $laundryId)->where('status', 'received')->count(),
            'cleaning'  => Order::where('laundry_id', $laundryId)->where('status', 'cleaning')->count(),
            'ready'     => Order::where('laundry_id', $laundryId)->where('status', 'ready')->count(),
            'delivered' => Order::where('laundry_id', $laundryId)->where('status', 'delivered')->count(),
            'total'     => Order::where('laundry_id', $laundryId)->count(),
            'total_clients' => Client::where('laundry_id', $laundryId)->count(),
            'today_orders' => Order::where('laundry_id', $laundryId)
                ->whereDate('created_at', today())
                ->count(),
            'pending_orders' => Order::where('laundry_id', $laundryId)
                ->whereIn('status', ['received', 'cleaning'])
                ->count(),
        ];

        // Dernières commandes
        $orders = Order::where('laundry_id', $laundryId)
            ->with('client')
            ->latest()
            ->take(10)
            ->get();

        // Clients récents
        $recentClients = Client::where('laundry_id', $laundryId)
            ->latest()
            ->take(5)
            ->get();

        return view('laundry.dashboard', compact('stats', 'orders', 'recentClients'));
    }
}