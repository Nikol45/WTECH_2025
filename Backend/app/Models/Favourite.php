<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    /** @use HasFactory<\Database\Factories\FavouriteFactory> */
    use HasFactory;

    public function farm_product() {
        return $this->belongsTo(FarmProduct::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
