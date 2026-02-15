<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;

//show home page
Route::get('/',[ProductController::class,'index'])->name('home');
//show products page (shop)
Route::get('/shop',[ProductController::class,'show'])->name('shop');
//show single product page
Route::get('/shop/{pId}',[ProductController::class,'details']);
//show categories page
Route::get('/categories',[CategoryController::class,'show'])->name('category');
//show customer user register page
Route::get('/register',[UserController::class,'show'])->name('register');
//store new customer user
Route::post('/user',[UserController::class,'store']);
//logout customer user
Route::post('/logout',[UserController::class,'logout']);
//show customer user login page
Route::get('/login',[UserController::class,'login'])->name('login');
//login authenticate customer user
Route::post('/authenticate',[UserController::class,'authenticate']);
//show customer user profile page
Route::get('/user/profile',[UserController::class,'profile'])->name('profile');
//update customer user profile image
Route::put('/user/profile/image/{user}',[UserController::class,'imageUpdate']);
//show customer user edit form
Route::get('/user/profile/edit',[UserController::class,'editCustomer'])->name('editCustomer');
//update customer user date
Route::put('/user/profile/edit/{user}',[UserController::class,'update']);
//store new review
Route::post('/addReview',[ReviewController::class,'store']);
//add to cart
Route::post('/add/cart/{product}',[CartController::class,'add'])->name('cart.add');
//show cart
Route::get('/cart',[CartController::class,'show'])->name('cart.show');
//update cart item
Route::put('/update/cart/{cartItem}',[CartController::class,'update'])->name('cart.update');
//remove cart item
Route::delete('/remove/cart/{cartItem}',[CartController::class,'remove'])->name('cart.remove');
//show checkout page
Route::get('/checkout',[CheckoutController::class,'show'])->name('checkout');
//procced payment
Route::post('/payment',[CheckoutController::class,'payment'])->name('payment');
//show single order 
Route::get('/order',[OrderController::class,'show'])->name('orders.customer');