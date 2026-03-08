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
use Illuminate\Support\Facades\Gate;

class Product extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    public function scopeFilter($query, array $filters){
        if ($filters['tags'] ?? false) {
            $categoryName = $filters['tags'];
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        if ($filters['search'] ?? false) {
            $search = $filters['search'];
            // group search conditions so they don't break other filters (AND)
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%')
                  ->orWhereHas('category', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // for admin products page to show deleted products (admin only)
        if ($filters['trashed'] ?? false) {
            $response = Gate::inspect('viewDeleted', Product::class);
            if ($response->allowed()) {
                // abort(403, $response->message());
                $query->onlyTrashed();
            }
        }

        return $query;
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
