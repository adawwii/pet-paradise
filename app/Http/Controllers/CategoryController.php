<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
// use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    
    //
    public function show() {
        
        $categories = $this->categoryService->showCategory();
        
        return view('categories.index',compact('categories'));
    }






    //api for category index
    public function showApi() {
        $categories=Category::with(['products'=> function($query){
            $query->with('reviews')
            ->withAvg('reviews','rating');
        }])
        // ->withAvg('reviews_avg_rating')
        ->withCount('products')
        ->orderByDesc('products_count')
        ->get();
        
        // return view('categories.index',compact('categories'));
        return response()->json($categories,200);
    }
}
