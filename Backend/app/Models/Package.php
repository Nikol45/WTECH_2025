<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

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
