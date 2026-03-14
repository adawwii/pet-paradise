<?php

namespace App\Http\Controllers;

use App\Models\Review;
// use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    //store Review
    public function store(Request $request) {
        
        // if(auth()->user()->cannot('make reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }
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
    //employee and admin show reveiws
    public function showAdmin() {
        // if(auth()->user()->cannot('manage reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }
        $reviews=Review::with(['product' => function($query) {
            $query->withTrashed();
        }])->with(['user' => fn($q) => $q->withTrashed()])
        ->filter(request()->only(['search','reviewStatus']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.review',compact('reviews'));
    }
    //employee and admin update status
    public function updateStatus(Review $review) {
        //auth
        // if(auth()->user()->cannot('manage reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }
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
    //employee and admin delete status
    public function deleteStatus(Review $review) {
        //auth
        // if(auth()->user()->cannot('manage reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }

        $review->delete();
        return back()->with('success','review deleted successfuly');
    }
}
