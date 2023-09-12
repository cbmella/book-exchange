<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exchanges() {
        return $this->hasMany(Exchange::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
