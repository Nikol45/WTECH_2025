<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'price',
        'expected_delivery_date',
        'status',
        'order_id', // ak používaš tiež mass assignment pri balíkoch ručne
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function farm() {
        return $this->belongsTo(Farm::class);
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
}
