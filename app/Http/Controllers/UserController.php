<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    //show register
    public function show() {
        return view('user.register');
    }
    //store new User 
    public function store(Request $request) {
        $user=$this->userService->storeUser($request);
        return redirect('/')->with('success',"Welcome $user->name");
    }
    //email varification handling
    public function emailVerification(EmailVerificationRequest $request) {
        $request->fulfill();
        $user = $request->user();
        $user->givePermissionTo(['make reviews','make orders']);
        return redirect()->route('profile')->with('success','Your account have been verified successfuly');
    }
    //email verification notice
    public function verificationNotice() {
        return back()->with('info','Verify your account to complete this action');
    }
    //logout user
    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success','You have been logged out!');
    }
    //show login page
    public function login() {
        return view('user.login');
    }
    //authenticate user login
    public function authenticate(Request $request) {
        $auth=$this->userService->authenticateUser($request);
        // dd($auth);
        //attempt login
       return $auth==true
            ? redirect('/')->with('success',"Welcome $auth->name")
            : back()->withErrors(['email' => 'Invalid Credentials!'])->onlyInput('email');
    }
    //show Profile
    public function profile() {
        return view('user.profile');
    }
    //Update image
    public function imageUpdate(Request $request , User $user) {
        //auth for user verification
        if($user->id != auth()->id()) {
            return redirect('/')->with('error', 'Unauthorized Action');
        }

        $attempt=$this->userService->profileImage($request,$user);
        if($attempt){
            return back()->with('success','Image Updated Successfully!');
        }
        //wrong data
        return back()->with('error','Try again later!');
    }
    //show customer user edit form
    public function editCustomer() {
        return view('user.edit_customer');
    }
    //update customer user data
    public function update(Request $request,User $user) {
        if($user->id != auth()->id()) {
            return redirect('/')->with('error','Unauthorized Action');
        }
        $this->userService->editCustomer($request, $user);
        //success redirect
        return redirect(route('profile'))->with('success','Profile updated successfully!');
    } 
    //employee and admin show all customers
    public function adminCustomers() {
        // if(auth()->user()->cannot('view dashboard')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        $customers=$this->userService->showCustomers();

        return view('user.admin_customers',compact('customers'));
    }
    //employee and admin delete customer
    public function deleteCustomer(User $customer) {
        // if(auth()->user()->cannot('manage customers')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }

        $customer->delete();
        return back()->with('success','Customer Deleted Successfully!');
    }
    //employee and admin restore customer
    public function restoreCustomer($customer) {
        // if(auth()->user()->cannot('manage customers')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        $this->userService->restoreCustomer($customer);
        return back()->with('success','Customer Restored Successfully!');
    }
    //employee and admin show customer's profile
    public function adminAnyProfile($customer) {
        // if(auth()->user()->cannot('manage customers')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        $data=$this->userService->customerProfile($customer);
        return view('user.admin_customer_profile',$data);
    }
    //superadmin show employees managment page
    public function showEmployees() {
        // if(auth()->user()->cannot('manage employees')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        $employees=$this->userService->showEmployees();
        return view('user.admin_employee_managment',compact('employees'));
    }
    //super admin show employee profile managment page
    public function adminEmployeeProfile($employee) {
        // if(auth()->user()->cannot('manage employees')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        $data=$this->userService->employeeProfile($employee);
        return view('user.admin_employee_profile',$data);
    }
    //super admin remove employee
    public function deleteEmployee(User $employee) {
        // if(auth()->user()->cannot('manage employees')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }

        $employee->delete();
        return back()->with('success','Employee removed Successfuly');
    }
    //super admin force delete employee
    public function forceDeleteEmployee($employee){
        $this->userService->forceDeleteEmployee($employee);
        return back()->with('success','Employee Permanently Deleted');
    }
    //super admin restore employee
    public function restoreEmployee($employee) {
        // if(auth()->user()->cannot('manage employees')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }

        $this->userService->restoreEmployee($employee);

        return back()->with('success','Employee restored successfuly');
    }
    //super admin register employee
    public function registerEmployee() {
        // if(auth()->user()->cannot('manage employees')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        return view('user.admin_employee_register');
    }
    //super admin store new employee
    public function storeEmployee(Request $request) {
        // if(auth()->user()->cannot('manage employees')) {
        //     return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        // }
        $this->userService->storeEmployee($request);

        return back()->with('success','employee registerd successfuly');
    }

}
