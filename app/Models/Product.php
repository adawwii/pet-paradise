<?php

namespace App\Models;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    public function scopeFilter($query, array $filters){
        if($filters['tags'] ?? false){
            $categoryName=request('tags');
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
                });
            }
        if ($filters['search'] ?? false) {
            $search=request('search');
            $query->where('name','like','%'.$search.'%')
            ->orWhere('description','like','%'.$search.'%')
            ->orWhereHas('category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                 });
            }
        }

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function users() {
        return $this->belongsTo(User::class);
    }
    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }


}
