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
use Spatie\Permission\Middleware\RoleMiddleware;

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
    //show employee and super admin login page
    Route::get('/admin/login',[AdminController::class,'showLogin'])->name('admin.login');
    //login authenticate employee and super admin page
    Route::post('/admin/authenticate',[AdminController::class,'authenticate'])->name('admin.authenticate');

});

//auhtenticated users
Route::middleware(['auth'])->group(function() {

//Email verfication handler
Route::get('/email/verify/{id}/{hash}',[UserController::class,'emailVerification'])->middleware('signed')->name('verification.verify');
//email verification notice
Route::get('/email/verify',[UserController::class,'verificationNotice'])->name('verification.notice');
//logout customer user
Route::post('/logout',[UserController::class,'logout']);
//show customer user profile page
Route::get('/user/profile',[UserController::class,'profile'])->name('profile');
//update customer user profile image
Route::patch('/user/profile/image/{user}',[UserController::class,'imageUpdate']);
//show customer user edit form
Route::get('/user/profile/edit',[UserController::class,'editCustomer'])->name('editCustomer');
//update customer user date
Route::put('/user/profile/edit/{user}',[UserController::class,'update']);
//store new review
Route::post('/addReview',[ReviewController::class,'store'])->middleware(['verified','can:make reviews']);
//add to cart
Route::post('/add/cart/{product}',[CartController::class,'add'])->name('cart.add');
//show cart
Route::get('/cart',[CartController::class,'show'])->name('cart.show');
//update cart item
Route::patch('/update/cart/{cartItem}',[CartController::class,'update'])->name('cart.update');
//remove cart item
Route::delete('/remove/cart/{cartItem}',[CartController::class,'remove'])->name('cart.remove');
//show checkout page
Route::get('/checkout',[CheckoutController::class,'show'])->name('checkout')->middleware(['verified','can:make orders']);
//procced payment
Route::post('/payment',[CheckoutController::class,'payment'])->name('payment')->middleware(['verified','can:make orders']);
//show single order 
Route::get('/order',[OrderController::class,'show'])->name('orders.customer');
//
Route::get('/order-processing',[OrderController::class,'orderProcessing'])->name('order.processing');
//
Route::get('/check-order',[OrderController::class,'check']);
});

//Employees and Super Admin routes
Route::middleware(['auth', RoleMiddleware::using('employee|Super Admin')])->group(function() {

//admin&employees dashboard
Route::get('/dashboard',[AdminController::class, 'index'])->name('dashboard');
//admin&employees logout
Route::post('/admin/logout',[AdminController::class,'logout'])->name('admin.logout');
//admin&employees show orders
Route::get('/admin/orders',[OrderController::class,'orders'])->name('admin.orders');
//admin&employees orders update order status
Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
//admin&employees orders export
Route::get('/admin/orders/export',[OrderController::class,'export'])->name('admin.orders.export');
//admin&employees single order details
Route::get('/admin/orders/{order}',[OrderController::class,'singleOrder'])->name('admin.orders.single');
//admin&employees show products management page
Route::get('/admin/products',[ProductController::class,'adminProducts'])->name('admin.products');
//admin&employees show add product page
Route::get('/admin/products/add',[ProductController::class,'addProduct'])->name('admin.products.add');
//admin&employees store new product
Route::post('/admin/products',[ProductController::class,'storeProduct'])->name('admin.product.store');
//admin&employees show edit product page
Route::get('/admin/products/{product}/edit',[ProductController::class,'editProduct'])->name('admin.product.edit');
//admin&employees update product
Route::put('/admin/products/{product}',[ProductController::class,'updateProduct'])->name('admin.product.update');
//admin&employees toggle product status
Route::patch('/admin/products/{product}/toggle', [ProductController::class, 'toggle'])->name('admin.product.toggle');
//admin&employees show product details page
Route::get('/admin/products/{product}',[ProductController::class,'productDetails'])->name('admin.products.details');
//admin&employees delete product
Route::delete('/admin/products/{product}',[ProductController::class,'deleteProduct'])->name('admin.product.delete');
//admin&employees show customers management page
Route::get('/admin/customers',[UserController::class,'adminCustomers'])->name('admin.customers');
//admin&employees delete customer
Route::delete('/admin/customers/{customer}',[UserController::class,'deleteCustomer'])->name('admin.customer.delete');
//admin&employees restore customer
Route::patch('/admin/customers/{customer}/restore',[UserController::class,'restoreCustomer'])->name('admin.customer.restore');
//admin&employees customer profile managment page
Route::get('/admin/customers/{customer}/profile',[UserController::class,'adminAnyProfile'])->name('admin.customer.profile');
//admin&employees show product's reviews
Route::get('/admin/reviews',[ReviewController::class,'showAdmin'])->name('admin.review.show');
//admin&employees update review status
Route::patch('/admin/review/{review}/status',[ReviewController::class,'updateStatus'])->name('admin.review.status');
//admin&employees delete review
Route::delete('/admin/review/{review}/delete',[ReviewController::class,'deleteStatus'])->name('admin.review.delete');
});

//Super Admin Routes
Route::middleware(['auth', RoleMiddleware::using('Super Admin')])->group( function (){

//Super admin restore product
Route::patch('/admin/products/{product}/restore',[ProductController::class,'restoreProduct'])->name('admin.product.restore');
//super admin show employees managment page
Route::get('/admin/employees',[UserController::class,'showEmployees'])->name('admin.show.employees');
//super admin employee profile managment page
Route::get('/admin/employees/{employee}/profile',[UserController::class,'adminEmployeeProfile'])->name('admin.employee.profile');
//super admin remove employee
Route::delete('/admin/employees/{employee}/delete',[UserController::class,'deleteEmployee'])->name('admin.employee.delete');
//super admin force delete employee
Route::delete('/admin/employees/{employee}/forceDelete',[UserController::class,'forceDeleteEmployee'])->name('admin.employee.forceDelete');
//super admin restore employee
Route::patch('/admin/employees/{employee}/restore',[UserController::class,'restoreEmployee'])->name('admin.employee.restore');
//super admin show register employee page
Route::get('/admin/employees/register',[UserController::class,'registerEmployee'])->name('admin.employee.register');
//super admin store new employee
Route::post('/admin/employees/store',[UserController::class,'storeEmployee'])->name('admin.employee.store');
});
