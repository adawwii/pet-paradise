<?php

namespace App\Http\Controllers;

use App\Models\Review;
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
}
