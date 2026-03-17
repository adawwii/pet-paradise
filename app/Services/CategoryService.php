<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    protected $category;
    /**
     * Create a new class instance.
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    //show categories
    public function showCategory(){
        $categories=$this->category->with(['products'=> function($query){
            $query->with('reviews')
            ->withAvg('reviews','rating');
        }])
        ->withCount('products')
        ->orderByDesc('products_count')
        ->get();
        return $categories;
    }
}
