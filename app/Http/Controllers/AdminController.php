<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //index
    public function index() {
        $productsCount=Product::count('id');
        $usersCount=User::where('role','customer')->count('id');
        $ordersCount=Order::count('id');
        $revenue=Order::where('status','completed')->sum('total');
        $orders=Order::latest()->paginate(5)->withQueryString();
        // dd($usersCount);
        return view('admin.dashboard',compact('productsCount','usersCount','ordersCount','revenue','orders'));
    }
}
