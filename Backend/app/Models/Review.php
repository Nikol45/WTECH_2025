<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_product_id',
        'rating',
        'title',
        'text',
        'reply',
    ];

    public function farm_product() {
        return $this->belongsTo(FarmProduct::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
