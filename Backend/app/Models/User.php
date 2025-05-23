<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'is_admin',
        'icon_id',
        'billing_address_id',
        'delivery_address_id',
        'company_id',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function icon() {
        return $this->belongsTo(Icon::class);
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

    public function farms() {
        return $this->hasMany(Farm::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function articles() {
        return $this->hasMany(Article::class);
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
