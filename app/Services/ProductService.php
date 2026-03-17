<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductService
{
    protected $product;
    /**
     * Create a new class instance.
     */
    public function __construct(Product $product)
    {
        $this->product=$product;
    }
    //get home page products Data
    public function homeData() {
         $topProducts = $this->product->with('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_active', true)
            ->orderByDesc('reviews_avg_rating')
            ->take(3)
            ->get();

        // // 4 highest reviewed categories (average rating of their products' reviews)
        $topCategories = Category::select('*')
        ->selectSub(function ($query) {
            $query->from('reviews')
            ->join('products', 'products.id', '=', 'reviews.product_id')
            ->where('products.is_active', true)
            ->whereColumn('products.category_id', 'categories.id')
            ->selectRaw('AVG(reviews.rating)');
            }, 'avg_rating')
            ->orderByDesc('avg_rating')
            ->take(4)
            ->get();
            info($topCategories);
        return compact('topProducts','topCategories');
    }
    //get shop products
    public function shopProducts(Request $request){
         $products=$this->product->with('category')
            ->withAvg(['reviews'=> fn($q) => $q->where('status', 'approved')], 'rating')
            ->withCount(['reviews'=> fn($q) => $q->where('status', 'approved')])
            ->filter($request->only('search','tags'))
            ->when($request->sort ?? false, function ($query, $sort) {
                if($sort=='rate'){
                    $query->withAvg('reviews', 'rating')
                          ->orderByDesc('reviews_avg_rating');
                }
                elseif($sort=='newest'){
                    $query->withAvg('reviews', 'rating')
                    ->latest();
                }
                elseif($sort=='oldest'){
                    $query->withAvg('reviews', 'rating')
                    ->oldest();
                }
        })
            ->where('is_active', true)
            ->paginate(8)
            ->withQueryString()
        ;
        return $products;
    }
    //get product profile data
    public function ProductDetails($id) {
        $product=$this->product->with('category')
        ->withAvg('reviews','rating')
        ->withCount(['reviews' => fn($q) => $q->where('status','approved')])
        ->where('is_active', true)
        ->find($id);
        if(!$product) {
            return redirect()->route('shop')->with("info","Product you're looking for does not exist!");
        }
        $reviews=Review::with(['user' => function($query) {
            $query->withTrashed();
        }])
        ->where('product_id',$product->id)
        ->where('status','approved')
        ->latest()
        ->simplePaginate(2);
    
        return compact('product','reviews');
    }
}
