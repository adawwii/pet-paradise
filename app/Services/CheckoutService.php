<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class CheckoutService
{
    protected $stripe;
    /**
     * Create a new class instance.
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }

    //payment transaction
    public function paymentConfiguration($formFields,User $user){
          try {

            $amount=$user->cart->cartItems->sum(function($item){
             return $item->product->price * $item->quantity;
             });

            //stripe payment configuration
           $this->stripe->setApiKey(config('services.stripe.secret'));
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

                return [
                    'client_secret'=> $paymentIntent->client_secret
                    ];

        } catch (Exception $e) {

            return [
            'success' => false,
            'validation_error' =>false,
            'message' => $e->getMessage()
        ];
        }
    }
    //payment webhook
    public function paymentWebhook(Request $request){
         $this->stripe->setApiKey(config('services.stripe.secret'));
    
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
        return ['error' => 'Invalid signature'];
        }

        // after successful payment
        if ($event->type === 'payment_intent.succeeded') {

        $paymentIntent = $event->data->object;

        $userId = $paymentIntent->metadata->user_id;
        $amount = $paymentIntent->amount / 100;
        

        $user = User::find($userId);

        if (!$user) {
            return ['error' => 'User not found'];
        }

        // checking repeating
        if (Order::where('stripe_payment_intent_id', $paymentIntent->id)->exists()) {
            return ['status' => 'already processed'];
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

         return ['status' => 'success'];
    }
}
