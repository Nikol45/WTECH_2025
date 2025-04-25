<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'farm_product_id',
        'package_id',
        'quantity',
        'price_when_ordered',
    ];

    public function farm_product() {
        return $this->belongsTo(FarmProduct::class);
    }

    public function package() {
        return $this->belongsTo(Package::class);
    }
}
