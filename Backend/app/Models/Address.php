<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    public function billingusers() {
        return $this->hasMany(Account::class, 'billing_address_id');
    }

    public function deliveryusers() {
        return $this->hasMany(Account::class, 'delivery_address_id');
    }

    public function farm() {
        return $this->hasOne(Farm::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
