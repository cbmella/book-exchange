<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    public function borrower() {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function lender() {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
