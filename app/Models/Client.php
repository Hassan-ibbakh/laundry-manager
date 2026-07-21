<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}