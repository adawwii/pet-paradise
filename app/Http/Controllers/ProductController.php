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
        return view('products.shop',compact('products'));
    }
    //show product Profile
    public function details($id) {
        $product=Product::with('category')
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
        // return response()->json($product,200);
        return view('products.productProfile',compact('product','reviews'));
    }
    //admin show products management page
    public function adminProducts(Request $request) { 
        $products=Product::with('category')
        ->with('reviews')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->filter($request->only('search','tags','trashed'))
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
        ->appends(request()->query())
        ;
        return view('products.admin_products',compact('products'));
    }
    //admin show add product page
    public function addProduct() {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Unauthorized action!');
        }
        //categories for select options
        $categories=Category::all();
        return view('products.admin_add_product',compact('categories'));
    }
    //admin store new product
    public function storeProduct(Request $request) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Unauthorized action!');
        }
        //validate request
        $formFields=$request->validate([
            'name'=>'required|string|max:255',
            'category'=>'required|exists:categories,id',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'is_active'=>'required|boolean',
            'description'=>'required|string',
        ]);
        $formFields['category_id']=$formFields['category'];
        unset($formFields['category']);
        $formFields['user_id']=auth()->id();
        //handle image upload
        if($request->hasFile('image_url')) {
            $formFields['image_url']=$request->file('image_url')->store('product_images','public');
        }
        //create product
        Product::create($formFields);
        //redirect with success message
        return redirect()->route('admin.products')->with('success','Product added successfully!');
    }
    //admin show edit product page
    public function editProduct(Product $product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Unauthorized action!');
        }
        //cannot edit active product
        if($product->is_active) {
            return back()->with('error','Cannot edit active product! Please deactivate it first.');
        }
        //categories for select options
        $categories=Category::all();
        return view('products.admin_edit_product',compact('product','categories'));
    }
    //admin update product
    public function updateProduct(Request $request, Product $product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Unauthorized action!');
        }
        //cannot edit active product
        if($product->is_active) {
            return redirect()->route('admin.products')->with('error','Cannot edit active product! Please deactivate it first.');
        }
        //validate request
        $formFields=$request->validate([
            'name'=>'required|string|max:255',
            'category'=>'required|exists:categories,id',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'is_active'=>'required|boolean',
            'description'=>'required|string',
        ]);
        $formFields['category_id']=$formFields['category'];
        unset($formFields['category']);
        //handle image upload
        if($request->hasFile('image_url')) {
            $formFields['image_url']=$request->file('image_url')->store('product_images','public');
        }
        //update product
        $product->update($formFields);
        //redirect with success message
        return redirect()->route('admin.products')->with('success','Product updated successfully!');
    }
    //admin toggle product status
    public function toggle($product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Unauthorized action!');
        }
        $product=Product::where('id',$product)
        ->first();
        //update product status
        $product->update(['is_active' => !$product->is_active]);
        //redirect with success message
        return back()->with('success','Product status updated successfully!');
    }
    //admin show product details page
    public function productDetails(Product $product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Unauthorized action!');
        }
        //product details with reviews
        $product=Product::with('category')
        ->with('reviews')
        ->withTrashed()
        ->withAvg('reviews','rating')
        ->withCount('reviews')
        ->find($product->id);
        //reviews with user details(+ deleted users)
        $reviews=Review::with(['user' => function ($query) {
            $query->withTrashed();
        }])        
        ->where('product_id',$product->id)
        ->latest()
        ->Paginate(10)
        ->withQueryString();   
        
        return view('products.admin_product_profile',compact('product','reviews'));
    }
    //admin delete product
    public function deleteProduct(Product $product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('login')->with('error','Unauthorized action!');
        }
        //cannot delete active product
        if($product->is_active) {
            return back()->with('error','Cannot delete active product! Please deactivate it first.');
        }
        //soft delete product
        $product->delete();
        return redirect()->route('admin.products')->with('success','Product deleted successfully!');
    }
    //admin restore product
    public function restoreProduct($product) {
        //auth check is admin
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('login')->with('error','Unauthorized action!');
        }
        $product=Product::onlyTrashed()
        ->where('id',$product)
        ->first();
        //restore product
        $product->restore();
        return back()->with('success','Product restored successfully!');
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
