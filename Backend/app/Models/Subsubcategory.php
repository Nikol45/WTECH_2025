<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsubcategory extends Model
{
    /** @use HasFactory<\Database\Factories\SubsubcategoryFactory> */
    use HasFactory;

    public function subcategory() {
        return $this->belongsTo(Subcategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
