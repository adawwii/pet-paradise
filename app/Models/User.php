<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Address;
use App\Models\Carts;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //scope filter for search and filter by email verified or not
    public function scopeFilter($query,array $filters) {
        if($filters['search'] ?? false) {
            $search=request('search');
            $query->where('name','like','%'.$search.'%')
            ->orWhere('email','like','%'.$search.'%')
            ->orWhere('phone_number','like','%'.$search.'%')
            ->orWhereHas('orders',function($q) use ($search) {
                $q->where('code','like','%'.$search.'%');
            });
        }
        if($filters['status'] ?? false) {
            $status=request('status');
            if($status == 'verified') {
                $query->whereNotNull('email_verified_at');
            } else if($status == 'unverified') {
                $query->whereNull('email_verified_at');
            } 
        }
        if($filters['account_type'] ?? false) {
            $type=request('account_type');
            if($type == 'trashed') {
                $query->onlyTrashed();
            } else if($type == 'all') {
                $query->withTrashed();
            }
        }
        return $query;
    }

    //RelationShips
    public function orders() {
        return $this->hasMany(Order::class);
        }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function cart() {
        return $this->hasOne(Carts::class);
    }
    public function addresses() {
        return $this->hasMany(Address::class);
    }
        

}
