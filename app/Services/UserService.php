<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserService
{
    protected $user;
    /**
     * Create a new class instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    //store new user
    public function storeUser(Request $request){
        //validate form data
        $formData=$request->validate([
            'name'=>['required','min:3','max:255'],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','confirmed','min:3','max:255']
        ]);
        //encrypt password
        $formData['password']=bcrypt($formData['password']);
        //store in the database
        $user=$this->user->create($formData)->assignRole('customer');
        event(new Registered($user));
        //login
        auth()->login($user);

        return $user;
    }
    //authenticate user
    public function authenticateUser(Request $request){
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
            $attempt=$user;
            return $attempt;
        }
        return $attempt=false;
    }
    //upload user profile image
    public function profileImage(Request $request,User $user){
        //data verification
        if($request->hasFile('profile_photo')){
            $formFields['image']=$request->file('profile_photo')
            ->store('logos','public')
            ;
            $user->update($formFields);
            return true;
        }
        return false;
    }
    //edit customer data
    public function editCustomer(Request $request,User $user){
        
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
        //check for the email 
        if($user->email !== $formData['email']){
            $user->revokePermissionTo(['make reviews','make orders']);
            $formData['email_verified_at']=null;
            $user->update($formData);
            $user->sendEmailVerificationNotification();
            return ;
        }
        //update action on database
        $user->update($formData);

        return ;
    }
    //show customers for admin
    public function showCustomers(){

        $customers=$this->user->withCount('orders')
        ->role('customer')
        ->filter(request()->only(['search','status','account_type']))
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return $customers;
    }
    //restore customer
    public function restoreCustomer($customer){
        $customer=$this->user->onlyTrashed()->where('id',$customer)->first();
        $customer->restore();
    }
    //admin and employee UI show cx profile
    public function customerProfile($customer){
        $customer=$this->user->withTrashed()
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
        return compact('customer','orders','reviews');
    }
    //show employees
    public function showEmployees(){
        $employees=$this->user->role('employee')
        ->filter(request()->only(['search','account_type']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return $employees;
    }
    //super admin UI show emp profile
    public function employeeProfile($employee){
        $employee=$this->user->withTrashed()
        ->where('id',$employee)
        ->first();
        $products=$employee->products()
        ->withTrashed()
        ->latest()
        ->paginate(5,['*'],'productPage')
        ->withQueryString();
        $reviews=$employee->reviews()->paginate(1,['*'],'reviewsPage')
        ->withQueryString();
        return compact('employee','products','reviews');
    }
    //restore employee
    public function restoreEmployee($employee){
        $employee=$this->user->onlyTrashed()
        ->role('employee')
        ->where('id',$employee)
        ->first();
        $employee->restore();
        return;
    }
    //store new employee
    public function storeEmployee(Request $request){
        //validate form data
        $formData=$request->validate([
            'name' => 'required||min:3||max:255',
            'email' => ['required','email',Rule::unique('users','email')],
            'password' => 'required||min:8||max:255||confirmed'
        ]);
        //encrypt password
        $formData['password']=bcrypt($formData['password']);

        //store in the database
        $user = $this->user->create($formData)->assignRole('employee');
        event(new Registered($user));

        return;
    }
    //force delete employee
    public function forceDeleteEmployee($employee){
        //retrieve employee from soft deleted data
        $employee=$this->user->withTrashed()->find($employee);

        $employee->forceDelete();
        return;
    }
}
