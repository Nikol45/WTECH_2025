<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'street_number',
        'zip_code',
        'city',
        'country',
        'address_type',
    ];

    public function billingUsers()
    {
        return $this->hasMany(Account::class, 'billing_address_id');
    }

    public function deliveryUsers()
    {
        return $this->hasMany(Account::class, 'delivery_address_id');
    }

    public function farm()
    {
        return $this->hasOne(Farm::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
