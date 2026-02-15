<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CheckoutController extends Controller
{
    //show checkout page
    public function show() {
        return view('components.checkout');
    }
    //payment transaction
    public function payment(Request $request) {
        //validating shipping address
        $validator = Validator::make($request->all(), [
            'street_address' => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'building'       => 'required|string|max:50',
            'apartment'      => 'required|string|max:50',
        ]);

        if($validator->fails()) {
            return response()->json([
                'validation_error' => true,
                 'errors' => $validator->errors(),
             ],422);
         }
         $formFields=$validator->validated();
         
        try {
            
          $response=  DB::transaction(function() use ($request,$formFields) {
            
                //stripe payment configuration
               Stripe::setApiKey(config('services.stripe.secret'));
                $amount=auth()->user()->cart->cartItems->sum(function($item){
                    return $item->product->price * $item->quantity;
                });
                $paymentIntent=PaymentIntent::create([
                    'amount'=>$amount * 100, //cents
                    'currency'=>'usd',
                    'payment_method'=>$request->payment_method,
                    'confirm'=>true,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                        ],
                ]);
                //inserting address
                $address=auth()->user()->addresses()->create($formFields);
                //inserting cart into orders
                $total=0;
                foreach(auth()->user()->cart->cartItems as $item) {
                    $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                }
                $order=auth()->user()->orders()->create([
                    'address_id'=>$address->id,
                    'total'=> $total,
                    'status'=>'processing'
                ]);
                //inserting cartItems into orderItems
                foreach(auth()->user()->cart->cartItems as $item){
                    $order->orderItems()->create([
                        'product_id'=>$item->product->id,
                        'quantity'=>$item->quantity
                    ]);
                }
                //empty cart
                auth()->user()->cart->cartItems()->delete();
                auth()->user()->cart()->delete();
                //return
                 return response()->json(['success'=>true]);

                });
                // return back()->with('success',"success transaction");
                // return response()->json(['success'=>true]);
                return $response;

        } catch (Exception $e) {
            // return back()->with('error','something went wrong');
            return response()->json([
            'success' => false,
            'validation_error' =>false,
            'message' => $e->getMessage()
        ]);
        }

    }
}
