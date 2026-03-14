<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
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
     $paymentIntentId = session('last_payment_intent');

      if (!$paymentIntentId) {
        return response()->json(['exists' => false]);
        }

     $order = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();

      if ($order) {
        session()->forget('last_payment_intent'); // clean session

        session()->flash('success', 'Your order has been placed successfully!');
         return response()->json([
            'exists' => true
         ]);
       }

     return response()->json([
        'exists' => false
      ]);
    }
    //show all orders for admin
    public function orders(Request $request) {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      $orders=Order::with(['user'=>function($query){
        $query->withTrashed();
      }])
      ->filter($request->only('search','status'))
      ->latest()
      ->paginate(8)
      ->withQueryString();
      return view('orders.admin_orders',compact('orders')); 
    }
    //update order status by admin
    public function updateStatus(Request $request,Order $order) {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      if($order->status === 'completed' || $order->status === 'cancelled' || $order->status === 'pending') {
        session()->flash('error','You cannot update status of completed, cancelled or pending orders');
        return response()->json(['error' => 'You cannot update status of completed, cancelled or pending orders']);
      }
      $status= $request->validate([
        'status'=>'required|string|in:pending,processing,shipped,completed,cancelled'
      ]);
      
      $order->update(['status'=>$status['status']]);
      session()->flash('success','Order status updated successfully');
      return response()->json(['message'=>'Order status updated successfully']);
    }
    //export orders to csv
    public function export() {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      $fileName = 'orders.csv';
      $orders = Order::with('user')->get();
      $headers = [
          'Content-Type' => 'text/csv',
          'Content-Disposition' => "attachment; filename=\"$fileName\"",
      ];
      $callback = function() use ($orders) {
          $file = fopen('php://output', 'w');
          fputcsv($file, ['ID', 'Customer', 'Total Amount', 'Status', 'Created At']);
          foreach ($orders as $order) {
              fputcsv($file, [
                  $order->code,
                  $order->user->name,
                  $order->total,
                  $order->status,
                  $order->created_at,
              ]);

              }
          fclose($file);
         };
      return response()->stream($callback, 200, $headers);
    }
    //show single order details for admin
    public function singleOrder(Order $order) {
      // if(auth()->user()->can('manage orders')) {
      //   return redirect()->route('admin.login')->with('error','Unauthorized Action');
      // }
      $user = $order->user()->withTrashed()->first();
      return view('orders.admin_single_order',compact('order','user'));
    }
}
