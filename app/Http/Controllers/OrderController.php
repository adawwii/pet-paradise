<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //show customer orders
    public function show() {
        $orders=auth()->user()->orders;
        return view('orders.customer_orders',compact('orders'));
    }
    //show order processing page
    public function orderProcessing(){
         if (!session()->has('last_payment_intent')) {
        return redirect()->route('home');
    }

    return view('orders.order-processing');
    }
    //checking order created 
    public function check() {
    $paymentIntentId = session('last_payment_intent');

    if (!$paymentIntentId) {
        return response()->json(['exists' => false]);
    }

    $order = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();

    if ($order) {
        session()->forget('last_payment_intent'); // clean session

        return response()->json([
            'exists' => true
        ]);
    }

    return response()->json([
        'exists' => false
    ]);
}

}
