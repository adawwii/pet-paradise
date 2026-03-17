<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService) {
         $this->productService = $productService;
    }
    
    //home page
    public function index() {
       $data=$this->productService->homeData();
        return view('products.index', $data);
        
    }
    //show products
    public function show(Request $request) {
         $products = $this->productService->shopProducts($request);
        return view('products.shop',compact('products'));
    }
    //show product Profile
    public function details($id) {
        $data=$this->productService->productDetails($id);
        return view('products.productProfile',$data);
    }
    //employee and admin show products management page
    public function adminProducts(Request $request) { 
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
        if(!auth()->user()->hasRole('Super Admin') && $request->has('trashed')) {
             $request->offsetUnset('trashed');
        }
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
    //employee and admin show add product page
    public function addProduct() {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
        //categories for select options
        $categories=Category::all();
        return view('products.admin_add_product',compact('categories'));
    }
    //employee and admin store new product
    public function storeProduct(Request $request) {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
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
    //employee and admin show edit product page
    public function editProduct(Product $product) {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
        //cannot edit active product
        if($product->is_active) {
            return back()->with('error','Cannot edit active product! Please deactivate it first.');
        }
        //categories for select options
        $categories=Category::all();
        return view('products.admin_edit_product',compact('product','categories'));
    }
    //employee and admin update product
    public function updateProduct(Request $request, Product $product) {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
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
        return redirect()->route('admin.products.details',$product->id)->with('success','Product updated successfully!');
    }
    //employee and admin toggle product status
    public function toggle($product) {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
        $product=Product::where('id',$product)
        ->first();
        //update product status
        $product->update(['is_active' => !$product->is_active]);
        //redirect with success message
        return back()->with('success','Product status updated successfully!');
    }
    //employee and admin show product details page
    public function productDetails($product) {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
        //product details with reviews
        $product=Product::with('category')
        ->with('reviews')
        ->withTrashed()
        ->withAvg('reviews','rating')
        ->withCount('reviews')
        ->find($product);
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
    //employee and admin delete product
    public function deleteProduct(Product $product) {
        //auth check is admin
        // if(auth()->user()->cannot('manage products')) {
        //     return back()->with('error','Unauthorized action');
        // }
        //cannot delete active product
        if($product->is_active) {
            return back()->with('error','Cannot delete active product! Please deactivate it first.');
        }
        //soft delete product
        $product->delete();
        return back()->with('success','Product deleted successfully!');
    }
    //Super admin restore product
    public function restoreProduct($product) {
        //auth check is admin
        // if(auth()->user()->hasRole('Super Admin')) {
        //     return back()->with('error','Unauthorized action');
        // }
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
