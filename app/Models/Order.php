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
        'service',
        'price',
        'received_at',
        'status',
        'payment_status',
        'delivery_required',
        'delivery_address',
        'tracking_token',
        'notes',
    ];

    protected $casts = [
        'received_at' => 'date',
        'price'       => 'decimal:2',
        'delivery_required' => 'boolean',
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
