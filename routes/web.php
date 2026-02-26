<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//show home page
Route::get('/',[ProductController::class,'index'])->name('home');
//show products page (shop)
Route::get('/shop',[ProductController::class,'show'])->name('shop');
//show single product page
Route::get('/shop/{pId}',[ProductController::class,'details']);
//show categories page
Route::get('/categories',[CategoryController::class,'show'])->name('category');
//unauthenticated users only
Route::middleware(['guest'])
->group(function() {
    //show customer user register page
    Route::get('/register',[UserController::class,'show'])->name('register');
    //store new customer user
    Route::post('/user',[UserController::class,'store']);
    //show customer user login page
    Route::get('/login',[UserController::class,'login'])->name('login');
    //login authenticate customer user
    Route::post('/authenticate',[UserController::class,'authenticate']);
});
Route::middleware(['auth'])
->group(function() {
//logout customer user
Route::post('/logout',[UserController::class,'logout']);
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
//
Route::get('/order-processing',[OrderController::class,'orderProcessing'])->name('order.processing');
//
Route::get('/check-order',[OrderController::class,'check']);
//admin dashboard
Route::get('/dashboard',[AdminController::class, 'index'])->name('dashboard')
->middleware('can:is-admin')
;
//admin logout
Route::post('/admin/logout',[AdminController::class,'logout'])->name('admin.logout');
//admin show orders
Route::get('/admin/orders',[OrderController::class,'orders'])->name('admin.orders')->middleware('can:is-admin');
//admin orders update order status
Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus')->middleware('can:is-admin');
//admin orders export
Route::get('/admin/orders/export',[OrderController::class,'export'])->name('admin.orders.export')->middleware('can:is-admin');
//admin single order details
Route::get('/admin/orders/{order}',[OrderController::class,'singleOrder'])->name('admin.orders.single')->middleware('can:is-admin');
//admin show products management page
Route::get('/admin/products',[ProductController::class,'adminProducts'])->name('admin.products')->middleware('can:is-admin');
//admin show add product page
Route::get('/admin/products/add',[ProductController::class,'addProduct'])->name('admin.products.add')->middleware('can:is-admin');
//admin store new product
Route::post('/admin/products',[ProductController::class,'storeProduct'])->name('admin.product.store')->middleware('can:is-admin');
//admin show edit product page
Route::get('/admin/products/{product}/edit',[ProductController::class,'editProduct'])->name('admin.product.edit')->middleware('can:is-admin');
//admin update product
Route::put('/admin/products/{product}',[ProductController::class,'updateProduct'])->name('admin.product.update')->middleware('can:is-admin');
//admin toggle product status
Route::patch('/admin/products/{product}/toggle', [ProductController::class, 'toggle'])->name('admin.product.toggle')->middleware('can:is-admin');
//admin show product details page
Route::get('/admin/products/{product}',[ProductController::class,'productDetails'])->name('admin.products.details')->middleware('can:is-admin');
//admin delete product
Route::delete('/admin/products/{product}',[ProductController::class,'deleteProduct'])->name('admin.product.delete')->middleware('can:is-admin');
});
Route::get('/admin/login',[AdminController::class,'showLogin'])->name('admin.login');
Route::post('/admin/authenticate',[AdminController::class,'authenticate'])->name('admin.authenticate');
