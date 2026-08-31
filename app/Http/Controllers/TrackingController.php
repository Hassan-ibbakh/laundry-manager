<?php

namespace App\Http\Controllers;

use App\Models\Order;
use TCPDF;

class TrackingController extends Controller
{
    public function show(string $tracking_token)
    {
        $order = Order::where('tracking_token', $tracking_token)
            ->with(['client', 'laundry', 'items'])
            ->firstOrFail();

        return view('tracking.show', compact('order'));
    }

    public function pdf(string $tracking_token)
    {
        $order = Order::where('tracking_token', $tracking_token)
            ->with(['client', 'laundry', 'items'])
            ->firstOrFail();

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
}