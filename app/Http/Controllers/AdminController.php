<?php

namespace App\Http\Controllers;

// use App\Models\Order;
// use App\Models\Product;
// use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService){
        $this->adminService = $adminService;
    }
    //index
    public function index() {
        // if(auth()->user()->cannot('view dashboard')) {
        //     return redirect()->route('admin.login')->with('error','Access denied! Admins only.');
        // }
        $data = $this->adminService->showDashboard();
        return view('admin.dashboard',$data);
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
        $attempt = $this->adminService->adminEmpAuth($request);
        
        if($attempt){
            return $attempt === 'permission granted'
               ? redirect()->route('dashboard')->with('success','Login successfully!')
               : back()->with('error','Access denied! Admins only.');
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
