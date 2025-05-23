<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'image',
        'user_id',
    ];

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
