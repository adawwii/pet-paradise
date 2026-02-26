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
            ->where('is_active', true)
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
        ->where('is_active', true)
        ->find($id);
        if(!$product) {
            return redirect()->route('shop')->with("info","Product you're looking for does not exist!");
        }
        $reviews=Review::with('user')
        ->where('product_id',$product->id)
        ->latest()
        ->simplePaginate(2);
        // return response()->json($product,200);
        return view('products.productProfile',compact('product','reviews'));
    }
    //admin show products management page
    public function adminProducts(Request $request) { 
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
        return view('products.admin_products',compact('products'));
    }
    //admin show add product page
    public function addProduct() {
        return view('products.admin_add_product');
    }
    //admin show edit product page
    public function editProduct(Product $product) {
        return view('products.admin_edit_product',compact('product'));
    }
    //admin show product details page
    public function productDetails(Product $product) {
        return view('products.admin_product_profile',compact('product'));
    }
    //admin delete product
    public function deleteProduct(Product $product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('login')->with('error','Unauthorized action!');
        }
        //soft delete product
        $product->delete();
        return back()->with('success','Product deleted successfully!');
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
