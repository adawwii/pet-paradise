<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    //store Review
    public function store(Request $request) {
        $response = Gate::inspect('create',Review::class);
        if($response->denied()) {
            return back()->with('error',$response->message());
        }
        $formData=$request->validate([
            'rating'=>'required',
            'comment'=>'required'
        ]);
        $formData['product_id']=$request->product;
        $formData['user_id']=auth()->user()->id;
        //add Review        
        Review::create($formData);
        return back()->with('success','Review posted successfully!');
    }
    //show reveiws for admins
    public function showAdmin() {
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }
        $reviews=Review::with(['product' => function($query) {
            $query->withTrashed();
        }])->with('user')
        ->filter(request()->only(['search','reviewStatus']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.review',compact('reviews'));
    }
    //update status
    public function updateStatus(Review $review) {
        //auth
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }
        //
        if(request()->has('approved')){
            $review->update([
                'status'=>'approved'
            ]);
        } else if(request()->has('rejected')) {
            $review->update([
                'status'=>'rejected'
            ]);
        } else {
            return back()->with('info','Unable to update status!');
        }
        return back()->with('success',"review's status updated successfuly");
    }
    //delete status
    public function deleteStatus(Review $review) {
        //auth
        $response=Gate::inspect('is-admin',User::class);
        if($response->denied()) {
            return redirect()->route('admin.login')->with('error','Unauthorized Action');
        }

        $review->delete();
        return back()->with('success','review deleted successfuly');
    }
}
