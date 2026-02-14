<?php

namespace App\Models;

use App\Models\User;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    //
        public function cartItems() {
        return $this->hasMany(CartItem::class , 'cart_id');
    }
    //
    public function user() {
        return $this->belongsTo(User::class);
    }
}
