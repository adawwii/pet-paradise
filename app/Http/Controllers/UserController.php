<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    //show register
    public function show() {
        return view('user.register');
    }
    //store new User 
    public function store(Request $request) {
        // dd($request);
        //validate form data
        $formData=$request->validate([
            'name'=>['required','min:3','max:255'],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','confirmed','min:3','max:255']
        ]);
        //encrypt password
        $formData['password']=bcrypt($formData['password']);
        //store in the database
        $user=User::create($formData);
        event(new Registered($user));
        //login
        auth()->login($user);
        
        return redirect('/')->with('success',"Welcome $user->name");
    }
    //email varification handling
    public function emailVerification(EmailVerificationRequest $request) {
        $request->fulfill();

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
        $formData=$request->validate([
            'email'=>['required','email'],
            'password'=>'required'
        ]);
        //remember me 
        $remember=$request->boolean('remember');
        //attempt to login
        if(auth()->attempt($formData,$remember)) {
            $request->session()->regenerate();
            $user=auth()->user();
            //attempt succesfuly
            return redirect('/')->with('success',"Welcome $user->name");
        }
        //attempt faild
        return back()->withErrors(['email' => 'Invalid Credentials!'])->onlyInput('email');
    }
    //show Profile
    public function profile() {
        // if(Gate::denies('is-employee')) {
        //     return redirect()->route('home')->with('error','unAuthourized Action!!!');
        // }
        return view('user.profile');
    }
    //Update image
    public function imageUpdate(Request $request , User $user) {
        //auth for user verification
        if($user->id != auth()->id()) {
            return redirect('/')->with('error', 'Unauthorized Action');
        }
        //data verification
        if($request->hasFile('profile_photo')){
            $formFields['image']=$request->file('profile_photo')
            ->store('logos','public')
            ;
            $user->update($formFields);
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
        $formData=$request->validate([
            'name'=>['required',
            'min:3','max:255'
            ],
            'email'=>[
                'required',
                'max:255',
                'email',
                Rule::unique('users','email')->ignore($user->id)
                ]
        ]);
        //check for changing in password
        if($request->filled('password')) {
            $validated_password=$request->validate([
                'password'=>[
                    'min:3',
                    'max:255',
                    'confirmed'
                ]
            ]);
            $formData['password']=bcrypt($validated_password['password']);

        }
        //update action on database
        $user->update($formData);
        //success redirect
        return redirect(route('profile'))->with('success','Profile updated successfully!');


    } 
    //employee and admin show all customers
    public function adminCustomers() {
        $response=Gate::inspect('is-admin-employee',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        }
        $customers=User::withCount('orders')
        ->where('role','customer')
        ->filter(request()->only(['search','status','account_type']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('user.admin_customers',compact('customers'));
    }
    //employee and admin delete customer
    public function deleteCustomer(User $customer) {
        $response=Gate::inspect('is-admin-employee',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        }

        $customer->delete();
        return back()->with('success','Customer Deleted Successfully!');
    }
    //employee and admin restore customer
    public function restoreCustomer($customer) {
        $response=Gate::inspect('is-admin-employee',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        }
        $customer=User::onlyTrashed()->where('id',$customer)->first();
        $customer->restore();
        return back()->with('success','Customer Restored Successfully!');
    }
    //employee and admin show customer's profile
    public function adminAnyProfile($customer) {
        $response=Gate::inspect('is-admin-employee',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        }
        $customer=User::withTrashed()
        ->where('id',$customer)
        ->first();
        $orders=$customer->orders()
        ->with('orderItems')
        ->with(['orderItems.product' => function($query) {
            $query->withTrashed();
        }])
        ->paginate(5,['*'],'ordersPage')
        ->withQueryString();
        $reviews=$customer->reviews()->paginate(1,['*'],'reviewsPage')
        ->withQueryString();
        return view('user.admin_customer_profile',compact('customer','orders','reviews'));
    }
    //superadmin show employees managment page
    public function showEmployees() {
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }
        $employees=User::where('role','employee')
        ->filter(request()->only(['search','account_type']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('user.admin_employee_managment',compact('employees'));
    }
    //super admin show employee profile managment page
    public function adminEmployeeProfile($employee) {
          $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action!');
        }
        $employee=User::withTrashed()
        ->where('id',$employee)
        ->first();
        $products=$employee->products()
        ->withTrashed()
        ->paginate(5,['*'],'productPage')
        ->withQueryString();
        $reviews=$employee->reviews()->paginate(1,['*'],'reviewsPage')
        ->withQueryString();
        return view('user.admin_employee_profile',compact('employee','products','reviews'));
    }
    //super admin remove employee
    public function deleteEmployee(User $employee) {
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }

        $employee->delete();
        return back()->with('success','Employee removed Successfuly');
    }
    //super admin restore employee
    public function restoreEmployee($employee) {
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }

        $employee=User::onlyTrashed()
        ->where('role','employee')
        ->where('id',$employee)
        ->first();
        $employee->restore();
        return back()->with('success','Employee restored successfuly');
    }
    //super admin register employee
    public function registerEmployee() {
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }
        return view('user.admin_employee_register');
    }
    //super admin store new employee
    public function storeEmployee(Request $request) {
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }
        //validate form data
        $formData=$request->validate([
            'name' => 'required||min:3||max:255',
            'email' => ['required','email',Rule::unique('users','email')],
            'password' => 'required||min:8||max:255||confirmed'
        ]);
        //encrypt password
        $formData['password']=bcrypt($formData['password']);
        //insert employee role
        $formData['role']='employee';
        //store in the database
        User::create($formData);

        return back()->with('success','employee registerd successfuly');
    }

}
