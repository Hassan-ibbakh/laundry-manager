<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use TCPDF;

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

        // Use a range so an index on created_at can be used.
        if ($request->filled('date')) {
            $query->whereBetween('created_at', [
                $request->date . ' 00:00:00',
                $request->date . ' 23:59:59',
            ]);
        }

        $orders = $query->latest()->paginate(15)->appends($request->query());

        return view('laundry.orders.index', compact('orders'));
    }

    public function create()
    {
        $selectedClientData = null;
        if ($clientId = old('client_id')) {
            $selectedClient = Client::where('laundry_id', $this->laundryId())
                ->find($clientId);

            if ($selectedClient) {
                $selectedClientData = [
                    'id' => $selectedClient->id,
                    'name' => $selectedClient->name,
                    'phone' => $selectedClient->phone,
                ];
            }
        }

        return view('laundry.orders.create', compact('selectedClientData'));
    }

    public function store(Request $request)
    {
        try {
            // Décoder le champ 'items' (JSON -> tableau PHP)
            $items = $request->input('items');
            if (is_string($items)) {
                $decodedItems = json_decode($items, true);
                if ($decodedItems === null) {
                    throw new \Exception('الرجاء اختيار عميل أو إضافة عميل جديد بالاسم والهاتف معاً.');
                }
                $request->merge(['items' => $decodedItems]);
            }

            // Validation STRICTE : vérifier les données client EN PREMIER
            $hasClientId = !empty($request->input('client_id'));
            $hasClientName = !empty($request->input('client_name'));
            $hasClientPhone = !empty($request->input('client_phone'));

            // Si aucun client existant, on DOIT avoir le nom ET le téléphone
            if (!$hasClientId && (!$hasClientName || !$hasClientPhone)) {
                throw new \Exception('الرجاء اختيار عميل أو إضافة عميل جديد بالاسم والهاتف معاً.');
            }

            // Validation du formulaire
            $validated = $request->validate([
                'client_id'          => [
                    'nullable',
                    'integer',
                    Rule::exists('clients', 'id')->where(
                        fn ($query) => $query->where('laundry_id', $this->laundryId())
                    ),
                ],
                'client_name'        => $hasClientId ? 'nullable' : 'required|string|max:255',
                'client_phone'       => $hasClientId ? 'nullable' : 'required|string|max:20',
                'received_at'        => 'required|date',
                'notes'              => 'nullable|string|max:1000',
                'items'              => 'required|array|min:1',
                'items.*.service'    => 'required|array|min:1',
                'items.*.service.*'  => ['required', Rule::in(['تصبين', 'مصلوح', 'صباغة', 'توصيل'])],
                'items.*.type'       => 'required|string|max:255',
                'items.*.color'      => 'nullable|string|max:255',
                'items.*.quantity'   => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
            ], [
                'items.required' => 'الرجاء إضافة قطعة واحدة على الأقل.',
                'items.min' => 'الرجاء إضافة قطعة واحدة على الأقل.',
                'items.array' => 'صيغة الأغراض غير صحيحة.',
                'items.*.service.required' => 'الخدمة مطلوبة لكل قطعة.',
                'items.*.service.in' => 'الخدمة غير صحيحة.',
                'items.*.type.required' => 'نوع القطعة مطلوب.',
                'items.*.quantity.required' => 'العدد مطلوب.',
                'items.*.quantity.min' => 'العدد يجب أن يكون 1 على الأقل.',
                'items.*.unit_price.required' => 'السعر مطلوب.',
                'items.*.unit_price.min' => 'السعر يجب أن يكون موجب.',
                'client_id.exists' => 'العميل غير موجود.',
                'client_name.required' => 'اسم العميل مطلوب.',
                'client_phone.required' => 'رقم الهاتف مطلوب.',
                'received_at.required' => 'تاريخ الاستلام مطلوب.',
                'received_at.date' => 'تاريخ الاستلام يجب أن يكون صحيح.',
            ]);

            // Gestion du client
            $clientId = $validated['client_id'] ?? null;

            if (!$clientId) {
                $clientName = $validated['client_name'];
                $clientPhone = $validated['client_phone'];

                // Vérifier si le client existe déjà par téléphone
                $client = Client::where('laundry_id', $this->laundryId())
                    ->where('phone', $clientPhone)
                    ->first();

                if (!$client) {
                    $client = Client::create([
                        'laundry_id' => $this->laundryId(),
                        'name'       => $clientName,
                        'phone'      => $clientPhone,
                    ]);
                }

                $clientId = $client->id;
            }

            // Calcul du total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['quantity'] * $item['unit_price'];
            }

            // Services uniques
            $servicesList = collect($validated['items'])
                ->pluck('service')
                ->flatten()
                ->unique()
                ->values()
                ->all();
            $globalService = implode(' + ', $servicesList);

            // Création de la commande — dans une transaction avec verrou pour éviter
            // toute collision de order_number si deux requêtes arrivent en même temps
            // (ex: double-clic, double soumission accidentelle)
            $order = null;
            $maxAttempts = 3;
            $attempt = 0;

            while ($attempt < $maxAttempts) {
                $attempt++;
                try {
                    $order = DB::transaction(function () use ($validated, $clientId, $globalService, $total) {
                        // lockForUpdate() verrouille les lignes correspondantes le temps
                        // de la transaction : aucune autre requête ne peut lire/générer
                        // le même numéro tant que celle-ci n'est pas terminée
                        $lastOrder = Order::orderBy('order_number', 'desc')
                            ->lockForUpdate()
                            ->first();

                        if ($lastOrder) {
                            preg_match('/(\d+)$/', $lastOrder->order_number, $matches);
                            $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;
                            $number = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                        } else {
                            $number = '00001';
                        }
                        $orderNumber = $number;

                        $orderData = [
                            'laundry_id'     => $this->laundryId(),
                            'client_id'      => $clientId,
                            'service'        => $globalService,
                            'received_at'    => $validated['received_at'],
                            'notes'          => $validated['notes'] ?? null,
                            'price'          => $total,
                            'status'         => 'received',
                            'order_number'   => $orderNumber,
                            'tracking_token' => Str::random(32),
                        ];

                        $newOrder = Order::create($orderData);

                        foreach ($validated['items'] as $item) {
                            $newOrder->items()->create([
                                'service'      => implode(' + ', $item['service']),
                                'pieces_type'  => $item['type'],
                                'pieces_color' => $item['color'] ?? null,
                                'quantity'     => $item['quantity'],
                                'unit_price'   => $item['unit_price'],
                                'total_price'  => $item['quantity'] * $item['unit_price'],
                            ]);
                        }

                        return $newOrder;
                    });

                    break; // succès, on sort de la boucle de tentatives

                } catch (\Illuminate\Database\QueryException $e) {
                    // Code 23000 = violation de contrainte d'intégrité (ex: order_number dupliqué)
                    $isDuplicate = $e->getCode() === '23000';
                    if ($isDuplicate && $attempt < $maxAttempts) {
                        Log::warning("⚠️ Collision order_number détectée, nouvelle tentative ($attempt/$maxAttempts)");
                        usleep(50000); // 50ms avant de réessayer
                        continue;
                    }
                    throw $e; // épuisé les tentatives, ou erreur différente : on relance
                }
            }

            // Open WhatsApp only after the order has been created successfully.
            return redirect()->route('laundry.orders.whatsapp', $order->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Order creation failed.', ['exception' => $e]);

            $message = $e->getMessage() ?: 'حدث خطأ أثناء إنشاء الطلب.';

            return back()->withErrors(['error' => $message])->withInput();
        }
    }

    public function show(int $id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with(['client', 'items'])
            ->findOrFail($id);
        return view('laundry.orders.show', compact('order'));
    }

    public function pdf(int $id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with(['client', 'items', 'laundry'])
            ->findOrFail($id);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setRTL(true);
        $pdf->SetCreator(config('app.name'));
        $pdf->SetTitle('Commande ' . $order->order_number);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTML(view('laundry.orders.pdf', compact('order'))->render(), true, false, true, false, '');
        $contents = $pdf->Output('commande-' . $order->order_number . '.pdf', 'S');

        return response()->streamDownload(
            fn () => print $contents,
            'commande-' . $order->order_number . '.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::where('laundry_id', $this->laundryId())->findOrFail($id);
        $request->validate(['status' => 'required|in:received,cleaning,ready,delivered']);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function whatsapp(int $id)
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
            . "💰 Prix total : {$order->price} DH\n"
            . "📅 Date : " . date('d/m/Y', strtotime((string) $order->received_at)) . "\n\n"
            . "🔗 Suivez votre commande :\n{$trackingUrl}";

        $phone = preg_replace('/\D/', '', $order->client->phone);
        $url = 'https://wa.me/'.$phone.'?text='.urlencode($message);

        return redirect($url);
    }
}