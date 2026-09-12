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

    private function nextOrderNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Y') . '-';
        $lastOrder = Order::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $lastSequence = 0;
        if ($lastOrder && preg_match('/(\d+)$/', $lastOrder->order_number, $matches)) {
            $lastSequence = (int) $matches[1];
        }

        return $prefix . str_pad((string) ($lastSequence + 1), 5, '0', STR_PAD_LEFT);
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
                  ->orWhereHas('client', function($c) use ($request) {
                      $c->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('phone', 'like', '%'.$request->search.'%');
                  });
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
                    throw new \Exception('الرجاء التحقق من محتوى السلة قبل إرسال الطلب.');
                }
                $request->merge(['items' => $decodedItems]);
            }

            // Validation client : un seul champ (nom ou téléphone) est accepté
            // lorsqu'aucun client existant n'est sélectionné.
            $hasClientId = !empty($request->input('client_id'));
            $hasClientName = !empty(trim((string) $request->input('client_name')));
            $hasClientPhone = !empty(trim((string) $request->input('client_phone')));

            if (!$hasClientId && !$hasClientName && !$hasClientPhone) {
                throw new \Exception('الرجاء إدخال اسم العميل أو رقم الهاتف، أو اختيار عميل موجود.');
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
                'client_name'        => 'nullable|string|max:255',
                'client_phone'       => 'nullable|string|max:20',
                'received_at'        => 'required|date',
                'notes'              => 'nullable|string|max:1000',
                'payment_status'     => ['required', Rule::in(['paid', 'unpaid'])],
                'delivery_required'  => 'required|boolean',
                'delivery_address'   => 'nullable|required_if:delivery_required,1|string|max:1000',
                'items'              => 'required|array|min:1|max:100',
                'items.*.service'    => 'required|array|min:1',
                'items.*.service.*'  => ['required', Rule::in(['تصبين', 'مصلوح', 'صباغة', 'أفرشة', 'توصيل'])],
                'items.*.type'       => 'required|string|max:255',
                'items.*.color'      => 'nullable|string|max:255',
                'items.*.dimensions' => 'nullable|string|max:50',
                'items.*.quantity'   => 'required|integer|min:1|max:10000',
                'items.*.unit_price' => 'required|numeric|min:0|max:1000000',
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
                'received_at.required' => 'تاريخ الاستلام مطلوب.',
                'received_at.date' => 'تاريخ الاستلام يجب أن يكون صحيح.',
                'delivery_address.required_if' => 'عنوان التوصيل مطلوب عندما يختار العميل التوصيل.',
                'delivery_address.max' => 'عنوان التوصيل طويل جداً.',
            ]);

            // Gestion du client
            $clientId = $validated['client_id'] ?? null;

            if (!$clientId) {
                $clientName = trim((string) ($validated['client_name'] ?? ''));
                $clientPhone = trim((string) ($validated['client_phone'] ?? ''));

                // Si le téléphone existe, on cherche d'abord par téléphone.
                $client = null;

                if ($clientPhone !== '') {
                    $client = Client::where('laundry_id', $this->laundryId())
                        ->where('phone', $clientPhone)
                        ->first();
                }

                // Sinon, on tente de retrouver le client par nom.
                if (!$client && $clientName !== '') {
                    $client = Client::where('laundry_id', $this->laundryId())
                        ->where('name', $clientName)
                        ->first();
                }

                if (!$client) {
                    $client = Client::create([
                        'laundry_id' => $this->laundryId(),
                        'name'       => $clientName,
                        'phone'      => $clientPhone !== '' ? $clientPhone : '',
                    ]);
                }

                $clientId = $client->id;
            }

            // Calcul du total
            $total = 0;
            foreach ($validated['items'] as $index => &$item) {
                if (in_array('أفرشة', $item['service'], true)) {
                    $dimensions = (string) ($item['dimensions'] ?? '');

                    if (!preg_match('/^([0-9]+(?:\.[0-9]+)?)x([0-9]+(?:\.[0-9]+)?)m$/', $dimensions, $matches)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            "items.$index.dimensions" => 'الطول والعرض مطلوبان لخدمة الأفرشة.',
                        ]);
                    }

                    $length = (float) $matches[1];
                    $width = (float) $matches[2];
                    if ($length <= 0 || $width <= 0) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            "items.$index.dimensions" => 'أبعاد الأفرشة يجب أن تكون أكبر من صفر.',
                        ]);
                    }

                    $item['unit_price'] = round($length * $width * 15, 2);
                }

                $total += $item['quantity'] * $item['unit_price'];
            }
            unset($item);

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
                        $orderNumber = $this->nextOrderNumber();

                        $orderData = [
                            'laundry_id'     => $this->laundryId(),
                            'client_id'      => $clientId,
                            'service'        => $globalService,
                            'received_at'    => $validated['received_at'],
                            'notes'          => $validated['notes'] ?? null,
                            'price'          => $total,
                            'status'         => 'received',
                            'payment_status' => $validated['payment_status'],
                            'delivery_required' => (bool) $validated['delivery_required'],
                            'delivery_address' => $validated['delivery_address'] ?? null,
                            'order_number'   => $orderNumber,
                            'tracking_token' => Str::random(32),
                        ];

                        $newOrder = Order::create($orderData);

                        foreach ($validated['items'] as $item) {
                            $newOrder->items()->create([
                                'service'      => implode(' + ', $item['service']),
                                'pieces_type'  => $item['type'],
                                'pieces_color' => $item['color'] ?? null,
                                'dimensions'   => $item['dimensions'] ?? null,
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
                        Log::warning("Collision order_number détectée, nouvelle tentative ($attempt/$maxAttempts)");
                        usleep(50000); // 50ms avant de réessayer
                        continue;
                    }
                    throw $e; // épuisé les tentatives, ou erreur différente : on relance
                }
            }

            // Afficher le bon de commande après un enregistrement réussi.
            return redirect()->route('laundry.orders.show', $order->id)
                ->with('success', 'تم حفظ الطلب بنجاح.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Order creation failed.', ['exception' => $e]);

            return back()->withErrors([
                'error' => 'حدث خطأ أثناء إنشاء الطلب. يرجى المحاولة مرة أخرى.',
            ])->withInput();
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

    public function ticket(int $id)
    {
        $order = Order::where('laundry_id', $this->laundryId())
            ->with(['client', 'items', 'laundry'])
            ->findOrFail($id);

        $pdf = new TCPDF('P', 'mm', [80, 220], true, 'UTF-8', false);
        $pdf->setRTL(true);
        $pdf->SetCreator(config('app.name'));
        $pdf->SetTitle('Ticket ' . $order->order_number);
        $pdf->SetMargins(4, 4, 4);
        $pdf->SetAutoPageBreak(true, 4);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->writeHTML(view('laundry.orders.ticket', compact('order'))->render(), true, false, true, false, '');
        $contents = $pdf->Output('ticket-' . $order->order_number . '.pdf', 'S');

        return response($contents, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ticket-' . $order->order_number . '.pdf"',
        ]);
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
            'received'  => 'تم الاستلام',
            'cleaning'  => 'قيد الغسيل',
            'ready'     => 'جاهز للاستلام',
            'delivered' => 'تم التسليم',
        ];

        $message = "*LaundryOS* - Suivi de commande\n\n"
            . "Client : {$order->client->name}\n"
            . "Commande : {$order->order_number}\n"
            . "Statut : {$statusLabels[$order->status]}\n"
            . "Prix total : {$order->price} DH\n"
            . "Date : " . date('d/m/Y', strtotime((string) $order->received_at)) . "\n\n"
            . "Suivez votre commande :\n{$trackingUrl}";

        $phone = preg_replace('/\D/', '', $order->client->phone);
        $url = 'https://wa.me/'.$phone.'?text='.urlencode($message);

        return redirect($url);
    }
}
