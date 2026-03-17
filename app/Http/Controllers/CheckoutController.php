<?php

namespace App\Http\Controllers;

// use App\Models\Order;
// use App\Models\User;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
// use Stripe\PaymentIntent;
use Stripe\Stripe;
// use Stripe\Webhook;


class CheckoutController extends Controller
{
    protected $checkoutService;
    
    public function __construct(CheckoutService $checkoutService){
        $this->checkoutService = $checkoutService;
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
         
         if($user->cannot('make orders')) {

             return response()->json([
            'success' => false,
            'validation_error' =>false,
            'message' => "Unauthorized Action!"
        ]);
         }
         
         //check the products are active and have enough stock before payment
         $product_availability=$user->cart->cartItems->every(function($item) {
            return $item->product->is_active && $item->product->stock >= $item->quantity;
         });
            if(!$product_availability) {
                return response()->json([
                    'success' => false,
                    'validation_error' =>false,
                    'message' => 'some products in your cart are not available or out of stock!'
                ]);
            }
         
            //payment attempt
        $attempt = $this->checkoutService->paymentConfiguration($formFields,$user);
       
        return response()->json($attempt);

    }
// payment stripe webhook
    public function webhook(Request $request) {
       $attempt = $this->checkoutService->paymentWebhook($request);
         return response()->json($attempt);
    }

    //show checkout page
    public function show() {
        $cart=auth()->user()->cart;
        if (!$cart) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }
        $response = Gate::inspect('view',$cart);
         if($response->denied()) {
            return redirect()->route('home')->with('error',$response->message());
         }
         $cart->load('cartItems.product');
        return view('components.checkout',compact('cart'));
    }
   
}
