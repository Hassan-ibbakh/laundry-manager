<?php

namespace App\Http\Controllers\Laundry;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        // Filtre par date unique
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(15)->appends($request->query());
        $clients = Client::where('laundry_id', $this->laundryId())->get();

        return view('laundry.orders.index', compact('orders', 'clients'));
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
            Log::info('=== DÉBUT CRÉATION COMMANDE ===');
            Log::info('Données reçues (raw):', ['items_raw' => $request->input('items')]);

            // Décoder le champ 'items' (JSON -> tableau PHP)
            $items = $request->input('items');
            if (is_string($items)) {
                $decodedItems = json_decode($items, true);
                if ($decodedItems === null) {
                    Log::error('❌ Erreur JSON_decode:', [
                        'raw' => $items,
                        'json_error' => json_last_error_msg(),
                    ]);
                    throw new \Exception('الرجاء اختيار عميل أو إضافة عميل جديد بالاسم والهاتف معاً.');
                }
                $request->merge(['items' => $decodedItems]);
                Log::info('✓ Items décodés avec succès:', $decodedItems);
            }

            // Validation STRICTE : vérifier les données client EN PREMIER
            $hasClientId = !empty($request->input('client_id'));
            $hasClientName = !empty($request->input('client_name'));
            $hasClientPhone = !empty($request->input('client_phone'));

            Log::info('Vérification client:', [
                'has_client_id' => $hasClientId,
                'has_client_name' => $hasClientName,
                'has_client_phone' => $hasClientPhone,
            ]);

            // Si aucun client existant, on DOIT avoir le nom ET le téléphone
            if (!$hasClientId && (!$hasClientName || !$hasClientPhone)) {
                throw new \Exception('الرجاء اختيار عميل أو إضافة عميل جديد بالاسم والهاتف معاً.');
            }

            // Validation du formulaire
            $validated = $request->validate([
                'client_id'          => 'nullable|integer|exists:clients,id',
                'client_name'        => $hasClientId ? 'nullable' : 'required|string|max:255',
                'client_phone'       => $hasClientId ? 'nullable' : 'required|string|max:20',
                'received_at'        => 'required|date',
                'notes'              => 'nullable|string|max:1000',
                'items'              => 'required|array|min:1',
                'items.*.service'    => 'required|in:غسيل,كي,غسيل+كي',
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

            Log::info('✓ Validation réussie');

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
                    Log::info('✓ Nouveau client créé ID: ' . $client->id);
                } else {
                    Log::info('✓ Client existant trouvé ID: ' . $client->id);
                }

                $clientId = $client->id;
            }

            // Calcul du total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['quantity'] * $item['unit_price'];
            }

            // Services uniques
            $servicesList = array_unique(array_column($validated['items'], 'service'));
            $globalService = !empty($servicesList) ? implode(', ', $servicesList) : 'غسيل';

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
                                'service'      => $item['service'],
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

            Log::info('✓ Commande créée ID: ' . $order->id . ', Numéro: ' . $order->order_number);
            Log::info('✓ Tous les ' . count($validated['items']) . ' articles créés');
            Log::info('=== FIN CRÉATION COMMANDE - SUCCÈS ===');

            // Redirection explicite vers la liste des commandes après création.
            return redirect()->to('/laundry/orders')
                ->with('success', 'تم إنشاء الطلب بنجاح.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Erreur de validation:', [
                'errors' => $e->errors(),
            ]);

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('❌ Erreur:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);

            $message = $e->getMessage() ?: 'حدث خطأ أثناء إنشاء الطلب.';

            return back()->withErrors(['error' => $message])->withInput();
        }
    }

    public function show($id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with(['client', 'items'])
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
            . "💰 Prix total : {$order->price} DH\n"
            . "📅 Date : {$order->received_at->format('d/m/Y')}\n\n"
            . "🔗 Suivez votre commande :\n{$trackingUrl}";

        $phone = preg_replace('/\D/', '', $order->client->phone);
        $url = 'https://wa.me/'.$phone.'?text='.urlencode($message);

        return redirect($url);
    }
}