<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        ];
        
        $laundries = Laundry::latest()->paginate(10);
        
        return view('admin.dashboard', compact('stats', 'laundries'));
    }
}