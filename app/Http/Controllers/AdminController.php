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
        if(auth()->user()->cannot('is-admin')) {
            return redirect()->route('admin.login')->with('error','Access denied! Admins only.');
        }
        $productsCount=Product::count('id');
        $usersCount=User::where('role','customer')->count('id');
        $ordersCount=Order::count('id');
        $revenue=Order::where('status','completed')->sum('total');
        $orders=Order::latest()->paginate(10)->withQueryString();
        // dd($usersCount);
        return view('admin.dashboard',compact('productsCount','usersCount','ordersCount','revenue','orders'));
    }
    //show login form
    public function showLogin() {
        
        return view('admin.login');
    }
    //authenticate admin user
    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]); 
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            if(auth()->user()->cannot('is-admin')) {
                auth()->logout();
                return redirect()->route('admin.login')->with('error','Access denied! Admins only.');
            }
            return redirect()->route('dashboard')->with('success','Login successfully!');
        }
    }
    //logout admin user
    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success','Logged out successfully!');
    }
}
