<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    //index
    public function index() {
        // if(auth()->user()->cannot('view dashboard')) {
        //     return redirect()->route('admin.login')->with('error','Access denied! Admins only.');
        // }
        $productsCount=Product::count('id');
        $usersCount=User::role('customer')->count('id');
        $ordersCount=Order::count('id');
        $revenue=Order::where('status','completed')->sum('total');
        $orders=Order::with([
            'user' => function($query) {
                $query->withTrashed();
            }
        ])->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.dashboard',compact('productsCount','usersCount','ordersCount','revenue','orders'));
    }
    //show login form
    public function showLogin() {
        //if admin or customer is already logged in
        // if (auth()->check()) {
        //     //admins redirect to dashboard & customers redirect to the customers home page
        //     return auth()->user()->can('view dashboard')
        //         ? redirect()->route('dashboard')->with('info','You are already logged in!')
        //         : redirect()->route('home')->with('error','Unauthorized Action');
        // }
        
        return view('admin.login');
    }
    //authenticate admin user
    public function authenticate(Request $request) {
        //if admin or customer is already logged in
        // if (auth()->check()) {
        //     //admins redirect to dashboard & customers redirect to the customers home page
        //     return auth()->user()->can('view dashboard')
        //         ? redirect()->route('dashboard')->with('info','You are already logged in!')
        //         : redirect()->route('home')->with('error','Unauthorized Action');
        // }
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]); 
        //remember me 
        $remember=$request->boolean('remember');

        if (auth()->attempt($credentials,$remember)) {

            if(auth()->user()->cannot('view dashboard')) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->with('error','Access denied! Admins only.');
            }
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success','Login successfully!');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    //logout admin user
    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success','Logged out successfully!');
    }
}
