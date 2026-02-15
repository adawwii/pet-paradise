<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function show() {
        $orders=auth()->user()->orders;
        return view('orders.customer_orders',compact('orders'));
    }
}
