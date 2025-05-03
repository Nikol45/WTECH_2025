<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    /** @use HasFactory<\Database\Factories\FarmFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address_id',
        'rating',
        'delivery_available',
        'min_delivery_price',
        'avg_delivery_time'
    ];

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }

    public function packages() {
        return $this->hasMany(Package::class);
    }

    public function farm_products() {
        return $this->hasMany(FarmProduct::class);
    }
}
