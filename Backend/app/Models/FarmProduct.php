<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmProduct extends Model
{
    /** @use HasFactory<\Database\Factories\FarmProductFactory> */
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'product_id',
        'sell_quantity',
        'price_sell_quantity',
        'price_per_unit',
        'unit',
        'discount_percentage',
        'farm_specific_description',
        'availability',
        'rating'
    ];

    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function farm() {
        return $this->belongsTo(Farm::class);
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function favourites() {
        return $this->hasMany(Favourite::class);
    }

    public function cart_items() {
        return $this->hasMany(CartItem::class);
    }
}
