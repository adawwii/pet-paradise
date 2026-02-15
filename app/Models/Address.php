<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $table='addresses';
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
