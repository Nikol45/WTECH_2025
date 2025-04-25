<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_address_id',
        'delivery_address_id',
        'company_id',
        'total_price',
        'payment_type',
        'delivery_type',
        'note',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function packages() {
        return $this->hasMany(Package::class);
    }

    public function billingAddress() {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function deliveryAddress() {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
