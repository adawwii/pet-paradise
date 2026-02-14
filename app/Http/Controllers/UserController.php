<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        //login
        auth()->login($user);
        
        return redirect('/')->with('success',"Welcome $user->name");
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
        //attempt to login
        if(auth()->attempt($formData)) {
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


}
