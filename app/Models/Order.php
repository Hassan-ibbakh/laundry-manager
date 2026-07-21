<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_id',
        'client_id',
        'order_number',
        'pieces_count',
        'pieces_type',
        'pieces_color',
        'service',
        'price',
        'received_at',
        'status',
        'tracking_token',
    ];

    protected $casts = [
        'received_at' => 'date', // ← AJOUTER CE CAST
        'price' => 'decimal:2',
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}