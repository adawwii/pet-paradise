<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index() {
        $topProducts = Product::with('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(3)
            ->get();

        // // 4 highest reviewed categories (average rating of their products' reviews)
        $topCategories = Category::select('*')
        ->selectSub(function ($query) {
            $query->from('reviews')
            ->join('products', 'products.id', '=', 'reviews.product_id')
            ->whereColumn('products.category_id', 'categories.id')
            ->selectRaw('AVG(reviews.rating)');
            }, 'avg_rating')
            ->orderByDesc('avg_rating')
            ->take(4)
            ->get();
            info($topCategories);
        return view('products.index', compact('topProducts','topCategories'));
        
    }

    //show products
    public function show(Request $request) {
            $products=Product::with('category')
            ->with('reviews')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
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
            ->paginate(8)
            ->withQueryString()
        ;
        return view('products.shop',compact('products'));
    }
    //show product Profile
    public function details($id) {
        $product=Product::with('category')
        ->withAvg('reviews','rating')
        ->withCount('reviews')
        ->find($id);
        $reviews=Review::with('user')
        ->where('product_id',$product->id)
        ->latest()
        ->simplePaginate(2);
        // return response()->json($product,200);
        return view('products.productProfile',compact('product','reviews'));
    }
        


        //api Test for show products shop route
        public function productsApi(Request $request) {
            $resultQuery=[
                'products'=>Product::with('category')
                ->with('reviews')
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->filter($request->only('search','tags'))
                ->when($request->sort ?? false, function ($query, $sort) {
                    if($sort=='rate'){
                        $query
                              ->orderByDesc('reviews_avg_rating');
                    }
                    elseif($sort=='newest'){
                        $query
                        ->latest();
                    }
                    elseif($sort=='oldest'){
                        $query
                        ->oldest();
                    }
            })
                ->paginate(8)
                ->withQueryString()
            ];
            // $resultJson=json_encode($resultQuery);
            return response()->json($resultQuery,200);
            // return $resultJson;
        }
        //api test for single product Route
        public function detailsApi($id){
        $product=Product::with('category')
        ->withAvg('reviews','rating')
        ->withCount('reviews')
        ->find($id);
        $reviews=Review::with('user')
        ->where('product_id',$product->id)
        ->latest()
        ->paginate(2);
        $data=[$product,$reviews];
        return response()->json($data,200);
            }

 }
