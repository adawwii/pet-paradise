<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewService
{
    protected $review;
    /**
     * Create a new class instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    //add product review
    public function addReview(Request $request){
         $formData=$request->validate([
            'rating'=>'required',
            'comment'=>'required'
        ]);
        $formData['product_id']=$request->product;
        $formData['user_id']=auth()->user()->id;
        //add Review        
        $this->review->create($formData);
        return;
    }
    //employee and admin show reveiws
    public function reviewData(){
         $reviews=$this->review->with(['product' => function($query) {
            $query->withTrashed();
        }])->with(['user' => fn($q) => $q->withTrashed()])
        ->filter(request()->only(['search','reviewStatus']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return $reviews;
    }
    //employee and admin update status
    public function updateReview(Request $request,Review $review){
         if($request->has('approved')){
            $review->update([
                'status'=>'approved'
            ]);
        } else if($request->has('rejected')) {
            $review->update([
                'status'=>'rejected'
            ]);
        } else {
            return false;
        }
        return true;
    }
}
