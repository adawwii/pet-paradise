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
Route::get('/shop/{pId}',[ProductController::class,'details'])->name('customer.product.profile');
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
//Email verfication handler
Route::get('/email/verify/{id}/{hash}',[UserController::class,'emailVerification'])->middleware('signed')->name('verification.verify');
//email verification notice
Route::get('/email/verify',[UserController::class,'verificationNotice'])->name('verification.notice');
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
Route::post('/addReview',[ReviewController::class,'store'])->middleware('verified');
//add to cart
Route::post('/add/cart/{product}',[CartController::class,'add'])->name('cart.add');
//show cart
Route::get('/cart',[CartController::class,'show'])->name('cart.show');
//update cart item
Route::put('/update/cart/{cartItem}',[CartController::class,'update'])->name('cart.update');
//remove cart item
Route::delete('/remove/cart/{cartItem}',[CartController::class,'remove'])->name('cart.remove');
//show checkout page
Route::get('/checkout',[CheckoutController::class,'show'])->name('checkout')->middleware('verified');
//procced payment
Route::post('/payment',[CheckoutController::class,'payment'])->name('payment')->middleware('verified');
//show single order 
Route::get('/order',[OrderController::class,'show'])->name('orders.customer');
//
Route::get('/order-processing',[OrderController::class,'orderProcessing'])->name('order.processing');
//
Route::get('/check-order',[OrderController::class,'check']);
//admin&employees dashboard
Route::get('/dashboard',[AdminController::class, 'index'])->name('dashboard')
->middleware('can:is-admin-employee')
;
//admin&employees logout
Route::post('/admin/logout',[AdminController::class,'logout'])->name('admin.logout');
//admin&employees show orders
Route::get('/admin/orders',[OrderController::class,'orders'])->name('admin.orders')->middleware('can:is-admin-employee');
//admin&employees orders update order status
Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus')->middleware('can:is-admin-employee');
//admin&employees orders export
Route::get('/admin/orders/export',[OrderController::class,'export'])->name('admin.orders.export')->middleware('can:is-admin-employee');
//admin&employees single order details
Route::get('/admin/orders/{order}',[OrderController::class,'singleOrder'])->name('admin.orders.single')->middleware('can:is-admin-employee');
//admin&employees show products management page
Route::get('/admin/products',[ProductController::class,'adminProducts'])->name('admin.products')->middleware('can:is-admin-employee');
//admin&employees show add product page
Route::get('/admin/products/add',[ProductController::class,'addProduct'])->name('admin.products.add')->middleware('can:is-admin-employee');
//admin&employees store new product
Route::post('/admin/products',[ProductController::class,'storeProduct'])->name('admin.product.store')->middleware('can:is-admin-employee');
//admin&employees show edit product page
Route::get('/admin/products/{product}/edit',[ProductController::class,'editProduct'])->name('admin.product.edit')->middleware('can:is-admin-employee');
//admin&employees update product
Route::put('/admin/products/{product}',[ProductController::class,'updateProduct'])->name('admin.product.update')->middleware('can:is-admin-employee');
//admin&employees toggle product status
Route::patch('/admin/products/{product}/toggle', [ProductController::class, 'toggle'])->name('admin.product.toggle')->middleware('can:is-admin-employee');
//admin&employees show product details page
Route::get('/admin/products/{product}',[ProductController::class,'productDetails'])->name('admin.products.details')->middleware('can:is-admin-employee');
//admin&employees delete product
Route::delete('/admin/products/{product}',[ProductController::class,'deleteProduct'])->name('admin.product.delete')->middleware('can:is-admin-employee');
//admin&employees restore product
Route::patch('/admin/products/{product}/restore',[ProductController::class,'restoreProduct'])->name('admin.product.restore')->middleware('can:is-admin-employee');
//admin&employees show customers management page
Route::get('/admin/customers',[UserController::class,'adminCustomers'])->name('admin.customers')->middleware('can:is-admin-employee');
//admin&employees delete customer
Route::delete('/admin/customers/{customer}',[UserController::class,'deleteCustomer'])->name('admin.customer.delete')->middleware('can:is-admin-employee');
//admin&employees restore customer
Route::patch('/admin/customers/{customer}/restore',[UserController::class,'restoreCustomer'])->name('admin.customer.restore')->middleware('can:is-admin-employee');
//admin&employees customer profile managment page
Route::get('/admin/customers/{customer}/profile',[UserController::class,'adminAnyProfile'])->name('admin.customer.profile')->middleware('can:is-admin-employee');
//admin&employees show product's reviews
Route::get('/admin/reviews',[ReviewController::class,'showAdmin'])->name('admin.review.show')->middleware('can:is-admin-employee');
//admin&employees update review status
Route::patch('/admin/review/{review}/status',[ReviewController::class,'updateStatus'])->name('admin.review.status')->middleware('can:is-admin-employee');
//admin&employees delete review
Route::delete('/admin/review/{review}/delete',[ReviewController::class,'deleteStatus'])->name('admin.review.delete')->middleware('can:is-admin-employee');
//super admin show employees managment page
Route::get('/admin/employees',[UserController::class,'showEmployees'])->name('admin.show.employees')->middleware('can:is-admin');
//super admin employee profile managment page
Route::get('/admin/employees/{employee}/profile',[UserController::class,'adminEmployeeProfile'])->name('admin.employee.profile')->middleware('can:is-admin');
//super admin remove employee
Route::delete('/admin/employees/{employee}/delete',[UserController::class,'deleteEmployee'])->name('admin.employee.delete')->middleware('can:is-admin');
//super admin restore employee
Route::patch('/admin/employees/{employee}/restore',[UserController::class,'restoreEmployee'])->name('admin.employee.restore')->middleware('can:is-admin');
//super admin show register employee page
Route::get('/admin/employees/register',[UserController::class,'registerEmployee'])->name('admin.employee.register')->middleware('can:is-admin');
//super admin store new employee
Route::post('/admin/employees/store',[UserController::class,'storeEmployee'])->name('admin.employee.store')->middleware('can:is-admin');
});
Route::get('/admin/login',[AdminController::class,'showLogin'])->name('admin.login');
Route::post('/admin/authenticate',[AdminController::class,'authenticate'])->name('admin.authenticate');
