<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    //admin and employee dashboard
    public function showDashboard(){
        $productsCount=Product::count();
        $usersCount=User::role('customer')->count();
        $ordersCount=Order::count();
        $revenue=Order::select('id','code','status','created_at','user_id','total')->where('status','completed')->sum('total');
        $orders=Order::with([
            'user' => function($query) {
                $query->withTrashed()
                ->select('id','name','deleted_at');
            }
        ])->latest()
        ->paginate(10)
        ->withQueryString();
        return compact('productsCount','usersCount','ordersCount','revenue','orders');
    }
    //admin and employees authentication
    public function adminEmpAuth(Request $request){
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
                return 'permission failed';
                }
                $request->session()->regenerate();
                return 'permission granted';
            }
        return false;
    }
}
