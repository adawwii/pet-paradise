<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/productsApi',[ProductController::class,'productsApi']);
Route::get('/productsApi/{pId}',[ProductController::class,'detailsApi']);
Route::get('/categories',[CategoryController::class,'showApi']);





// Route::get('/apiTest',function(){
//     return response()->json(
//         [
//         'posts'=>
//         [
//             'title'=>'Post One'
//         ]
//     ]);
// });