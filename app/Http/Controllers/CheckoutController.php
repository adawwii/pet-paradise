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
        $user=auth()->user();
        if(!$user->cart || $user->cart->cartItems->isEmpty()) {
            throw new Exception('cart is empty');
        }
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
         $amount=$user->cart->cartItems->sum(function($item){
             return $item->product->price * $item->quantity;
         });
         
        try {
            $order=  DB::transaction(function() use ($user,$formFields,$amount) {
            
                //inserting address
                $address=$user->addresses()->create($formFields);
                //inserting cart into orders
                
                $order=$user->orders()->create([
                    'address_id'=>$address->id,
                    'total'=> $amount,
                    'status'=>'pending'
                ]);
                //inserting cartItems into orderItems
                foreach($user->cart->cartItems as $item){
                    $order->orderItems()->create([
                        'product_id'=>$item->product->id,
                        'quantity'=>$item->quantity
                    ]);
                }
                return $order;
                });
               

                
            //stripe payment configuration
           Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent=PaymentIntent::create([
                'amount'=>$amount * 100, //cents
                'currency'=>'usd',
                'payment_method'=>$request->payment_method,
                'confirm'=>true,
                'metadata' =>[
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                    ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                    ],
            ]);
            if($paymentIntent->status !== 'succeeded')
                {
                    throw new Exception('payment failed or need more action!');
                }
            $response=  DB::transaction(function() use ($user,$order) {
                //updating order status after successful payment
                $order->update([
                    'status'=>'processing'
                ]);
                $user->cart->cartItems()->delete();
                $user->cart()->delete();
          //return
                 return 'success';
                });
                if($response !== 'success'){
                    throw new Exception('Payment failed , facing problem with creating your order');
                }
                return response()->json(['success'=>true]);

        } catch (Exception $e) {

            return response()->json([
            'success' => false,
            'validation_error' =>false,
            'message' => $e->getMessage()
        ]);
        }

    }
}
