<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/productsApi',[ProductController::class,'productsApi']);
Route::get('/productsApi/{pId}',[ProductController::class,'detailsApi']);
Route::get('/categories',[CategoryController::class,'showApi']);
Route::post('/stripe/webhook',[CheckoutController::class,'webhook']);
// Route::post('/payment',[CheckoutController::class,'payment'])->name('payment');






// Route::get('/apiTest',function(){
//     return response()->json(
//         [
//         'posts'=>
//         [
//             'title'=>'Post One'
//         ]
//     ]);
// });