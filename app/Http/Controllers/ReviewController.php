<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    protected $reviewService;
    
    public function __construct(ReviewService $reviewService){
        $this->reviewService = $reviewService;
    }
    //store Review
    public function store(Request $request) {
        
        // if(auth()->user()->cannot('make reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }
       $this->reviewService->addReview($request);
        return back()->with('success','Your review has been submitted and will be published shortly.');
    }
    //employee and admin show reveiws
    public function showAdmin() {
        // if(auth()->user()->cannot('manage reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }
        $reviews=$this->reviewService->reviewData();
        return view('admin.review',compact('reviews'));
    }
    //employee and admin update status
    public function updateStatus(Request $request,Review $review) {
        //auth
        // if(auth()->user()->cannot('manage reviews')) {
        //     return back()->with('error','Unauthorized action');
        // }
        
        $attempt=$this->reviewService->updateReview($request,$review);
        return $attempt
            ? back()->with('success',"review's status updated successfuly")
            : back()->with('info','Unable to update status!');
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
