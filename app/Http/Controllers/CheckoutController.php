<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;


class CheckoutController extends Controller
{
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
         $response = Gate::inspect('create',Order::class);
         if($response->denied()) {

             return response()->json([
            'success' => false,
            'validation_error' =>false,
            'message' => $response->message()
        ]);
         }
         $amount=$user->cart->cartItems->sum(function($item){
             return $item->product->price * $item->quantity;
         });
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
         
        try {

            //stripe payment configuration
           Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent=PaymentIntent::create([
                'amount'=>$amount * 100, //cents
                'currency'=>'usd',
                'metadata' =>[
                        'user_id' => $user->id,
                        'street_address' => $formFields['street_address'],
                        'city' => $formFields['city'],
                        'district' => $formFields['district'],
                        'building' => $formFields['building'],
                        'apartment' => $formFields['apartment'],
                    ],
            ]);

                session([
                    'last_payment_intent'=>$paymentIntent->id
                ]);

                return response()->json([
                    'client_secret'=> $paymentIntent->client_secret
                    ]);

        } catch (Exception $e) {

            return response()->json([
            'success' => false,
            'validation_error' =>false,
            'message' => $e->getMessage()
        ]);
        }

    }
// payment stripe webhook
    public function webhook(Request $request) {
        Stripe::setApiKey(config('services.stripe.secret'));
    
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

         try {
        $event = Webhook::constructEvent(
            $payload,
            $sigHeader,
            $endpointSecret
        );
         } catch (\Exception $e) {
        return response()->json(['error' => 'Invalid signature'], 400);
        }

        // after successful payment
        if ($event->type === 'payment_intent.succeeded') {

        $paymentIntent = $event->data->object;

        $userId = $paymentIntent->metadata->user_id;
        $amount = $paymentIntent->amount / 100;
        

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // checking repeating
        if (Order::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
            return response()->json(['status' => 'already processed']);
        }

        DB::transaction(function () use ($user, $paymentIntent, $amount) {

            $address = $user->addresses()->create([
                'street_address'=>$paymentIntent->metadata->street_address,
                'city'=>$paymentIntent->metadata->city,
                'district'=>$paymentIntent->metadata->district,
                'building'=>$paymentIntent->metadata->building,
                'apartment'=>$paymentIntent->metadata->apartment
            ]);

            $order = $user->orders()->create([
                'address_id' => $address->id,
                'total' => $amount,
                'status' => 'processing',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid' => true,
                'paid_at' => now(),
            ]);

            $orderItems= $user->cart->cartItems->map(function($item) use ($order) {
                return [
                    'order_id'=>$order->id,
                    'product_id'=>$item->product_id,
                    'quantity'=>$item->quantity,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ];
            })->toArray();

            DB::table('order_items')->insert($orderItems);

            $user->cart->cartItems()->delete();
            $user->cart()->delete();
        });
         }

         return response()->json(['status' => 'success']);
    }

    //show checkout page
    public function show() {
        $response = Gate::inspect('view',auth()->user()->cart);
         if($response->denied()) {
            return redirect()->route('home')->with('error',$response->message());
         }
        return view('components.checkout');
    }
   
}
