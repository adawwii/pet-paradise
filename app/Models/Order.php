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
        // Generate unique order code on creating
        static::creating(function($order) {
            do {
                $code = '#' . Str::upper(Str::random(6)) . 'ord';
            } while (static::where('code',$code)->exists());
            $order->code=$code;
        });
    }
    //scope for filtering orders
    public function scopeFilter($query, array $filters) {
        if ($filters['search'] ?? false) {
            $search=request('search');
            $query->where('code','like','%'.$search.'%')
            ->orWhere('status','like','%'.$search.'%')
            ->orWhereHas('address', function ($q) use ($search) {
                $q->Where('street_address', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ;
                 })
            ->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                 });
            }
        if ($filters['status'] ?? false) {
            if($filters['status']!='') {
                $query->where('status', request('status'));
            }
        }
    }

    //relationships
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
