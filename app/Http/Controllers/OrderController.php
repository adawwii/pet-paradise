<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    protected $orderService;
    
    public function __construct(OrderService $orderService){
      $this->orderService = $orderService;
    }
    //show customer orders
    public function show() {
      // $response = Gate::inspect('viewAny',Order::class);
      // if($response == false) {
      //   return redirect()->route('home')->with('error','Unauthorized action');
      // }
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
        if (!session()->has('last_payment_intent')) {
        return redirect()->route('home'); 
        }
        $attempt=$this->orderService->orderCreationCheck();

     

        return $attempt
           ? response()->json(['exists' => true])
           : response()->json(['exists' => false ]);
    }
    //show all orders for admin
    public function orders(Request $request) {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      $orders=$this->orderService->allOrders($request);
      return view('orders.admin_orders',compact('orders')); 
    }
    //update order status by admin
    public function updateStatus(Request $request,Order $order) {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      
      $attempt = $this->orderService->updateOrderStatus($request,$order);
      
      return $attempt
          ? response()->json(['message'=>'Order status updated successfully'])
          : response()->json(['error' => 'You cannot update status of completed, cancelled or pending orders']);
    }
    //export orders to csv
    public function export() {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
     
      $attempt=$this->orderService->orderCsv();

      return $attempt;
    }
    //show single order details for admin
    public function singleOrder(Order $order) {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      $order->load([
        'user' => fn($q) => $q->withTrashed(),
        'address' => fn($q) => $q->withTrashed()
        ]);
      // dd($order);
      return view('orders.admin_single_order',compact('order'));
    }
}
