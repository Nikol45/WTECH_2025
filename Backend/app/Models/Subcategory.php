<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    /** @use HasFactory<\Database\Factories\SubcategoryFactory> */
    use HasFactory;

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subsubcategories() {
        return $this->hasMany(Subsubcategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
