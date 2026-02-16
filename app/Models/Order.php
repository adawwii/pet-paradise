<?php

namespace App\Models;

use App\Models\Address;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    //
    protected static function boot() {
        parent::boot();
        static::creating(function($order) {
            do {
                $code = '#' . Str::upper(Str::random(6)) . 'ord';
            } while (static::where('code',$code)->exists());
            $order->code=$code;
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function address() {
        return $this->belongsTo(Address::class);
    }
}
