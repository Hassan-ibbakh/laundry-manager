<?php

namespace App\Http\Controllers;

use App\Models\Order;

class TrackingController extends Controller
{
    public function show($tracking_token)
    {
        $order = Order::where('tracking_token', $tracking_token)
            ->with(['client', 'laundry'])
            ->firstOrFail();

        return view('tracking.show', compact('order'));
    }
}