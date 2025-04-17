<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    /** @use HasFactory<\Database\Factories\IconFactory> */
    use HasFactory;

    public function users() {
        return $this->hasMany(Account::class, 'icon_id');
    }
}
