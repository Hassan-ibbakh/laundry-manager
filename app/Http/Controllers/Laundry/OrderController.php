<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private function laundryId()
    {
        return auth('laundry')->id();
    }

    public function index(Request $request)
    {
        $query = Order::where('laundry_id', $this->laundryId())->with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('client', fn($c) => $c->where('name', 'like', '%'.$request->search.'%'));
            });
        }

        $orders = $query->latest()->paginate(15);
        $clients = Client::where('laundry_id', $this->laundryId())->get();
        
        return view('laundry.orders.index', compact('orders', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('laundry_id', $this->laundryId())->get();
        return view('laundry.orders.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'    => 'nullable|exists:clients,id',
            'client_name'  => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'pieces_count' => 'required|integer|min:1',
            'pieces_type'  => 'required|string|max:255',
            'pieces_color' => 'nullable|string|max:255',
            'service'      => 'required|in:غسيل,كي,غسيل+كي',
            'price'        => 'required|numeric|min:0',
            'received_at'  => 'required|date', // ← Changé de received_date à received_at
            'notes'        => 'nullable|string',
        ]);

        // Créer client si nouveau
        if (empty($data['client_id'])) {
            $client = Client::create([
                'laundry_id' => $this->laundryId(),
                'name'       => $data['client_name'],
                'phone'      => $data['client_phone'],
            ]);
            $data['client_id'] = $client->id;
        }

        // Supprimer les champs temporaires
        unset($data['client_name'], $data['client_phone']);

        // Ajouter les données de la commande
        $data['laundry_id'] = $this->laundryId();
        $data['status'] = 'received';

        // Générer order_number et tracking_token
        $data['order_number'] = $this->generateOrderNumber();
        $data['tracking_token'] = Str::random(32);

        $order = Order::create($data);

        return redirect()->route('laundry.orders.show', $order->id)
            ->with('success', 'تم إنشاء الطلب بنجاح.');
    }

    public function show($id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with('client')
            ->findOrFail($id);
        return view('laundry.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('laundry_id', $this->laundryId())->findOrFail($id);
        $request->validate(['status' => 'required|in:received,cleaning,ready,delivered']);
        $order->update(['status' => $request->status]);
        
        return back()->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function pdf($id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with(['client', 'laundry'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.order', compact('order'));
        return $pdf->stream('order-'.$order->order_number.'.pdf');
    }

    public function whatsapp($id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with('client')
            ->findOrFail($id);

        $trackingUrl = route('tracking.show', $order->tracking_token);

        $statusLabels = [
            'received'  => '📥 تم الاستلام',
            'cleaning'  => '🧺 قيد الغسيل',
            'ready'     => '✅ جاهز للاستلام',
            'delivered' => '📦 تم التسليم',
        ];

        $message = "🧺 *LaundryOS* - Suivi de commande\n\n"
            . "👤 Client : {$order->client->name}\n"
            . "📋 Commande : {$order->order_number}\n"
            . "📊 Statut : {$statusLabels[$order->status]}\n"
            . "💰 Prix : {$order->price} DH\n"
            . "📅 Date : {$order->received_at->format('d/m/Y')}\n\n"
            . "🔗 Suivez votre commande :\n{$trackingUrl}";

        $phone = preg_replace('/\D/', '', $order->client->phone);
        $url = 'https://wa.me/'.$phone.'?text='.urlencode($message);

        return redirect($url);
    }

    private function generateOrderNumber()
    {
        $prefix = 'CMD-' . date('Ymd');
        $lastOrder = Order::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $number = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $number = '0001';
        }

        return $prefix . '-' . $number;
    }
}