<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Laundry;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLaundries = Laundry::count();
        $activeLaundries = Laundry::where('is_active', true)->count();

        $stats = [
            'total_laundries' => $totalLaundries,
            'active_laundries' => $activeLaundries,
            'active_percentage' => $totalLaundries > 0
                ? round(($activeLaundries / $totalLaundries) * 100)
                : 0,
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'total_clients' => Client::count(),
        ];

        $laundries = Laundry::withCount('orders')->latest()->paginate(8);

        return view('admin.dashboard', compact('stats', 'laundries'));
    }
}
