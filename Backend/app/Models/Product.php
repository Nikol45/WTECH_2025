<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subcategory() {
        return $this->belongsTo(Subcategory::class);
    }

    public function subsubcategory() {
        return $this->belongsTo(Subsubcategory::class);
    }

    public function farm_products() {
        return $this->hasMany(FarmProduct::class);
    }
}
