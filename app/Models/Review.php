<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    //
    use HasFactory;

    public function scopeFilter($query, array $filters) {
        if(!empty($filters['search'])) {
            $search=$filters['search'];
            $query->where('comment','like','%'.$search.'%')
            ->orWhereHas('user',function($q) use ($search) {
                $q->where('name','like','%'.$search.'%')
                ->withTrashed();
            })
            ->orWhereHas('product',function($q) use ($search) {
                $q->where('name','like','%'.$search.'%')
                ->withTrashed();
            })
            ;
        }
        if(!empty($filters['reviewStatus'])) {
            $status=$filters['reviewStatus'];
            if($status !='all'){
                $query->where('status',$status);
                }
        }
        return $query;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
