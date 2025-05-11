<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'ico',
        'ic_dph',
    ];

    public function users() {
        return $this->hasMany(Account::class, 'company_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
