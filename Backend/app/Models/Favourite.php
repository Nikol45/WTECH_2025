<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    /** @use HasFactory<\Database\Factories\FavouriteFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_product_id',
    ];

    public function farm_product() {
        return $this->belongsTo(FarmProduct::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
