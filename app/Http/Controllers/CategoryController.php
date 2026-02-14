<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function show() {
        $categories=Category::with(['products'=> function($query){
            $query->with('reviews')
            ->withAvg('reviews','rating');
        }])
        ->withCount('products')
        ->orderByDesc('products_count')
        ->get();
        
        return view('categories.index',compact('categories'));
        // return response()->json($categories,200);
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
