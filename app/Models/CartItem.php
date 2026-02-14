<?php

namespace App\Models;

use App\Models\Carts;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
     public function cart() {
        return $this->belongsTo(Carts::class, 'cart_id');
    }
    //
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
