<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{
    protected $order;
    /**
     * Create a new class instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    //check order created after payment integration
    public function orderCreationCheck(){

     $paymentIntentId = session('last_payment_intent');

      if (!$paymentIntentId) {
        return false;
        }

     $order = $this->order->where('stripe_payment_intent_id', $paymentIntentId)->first();

      if ($order) {
        session()->forget('last_payment_intent'); // clean session

        session()->flash('success', 'Your order has been placed successfully!');
         return true;
       }

     return false;
    }
    //show all orders for admin
    public function allOrders(Request $request){

        $orders=Order::with(['user'=>function($query){
        $query->withTrashed();
      }])
      ->with(['address'=>fn($q)=>$q->withTrashed()])
      ->filter($request->only('search','status'))
      ->latest()
      ->paginate(8)
      ->withQueryString();
      return $orders;
    }
    //update order status by admin
    public function updateOrderStatus(Request $request, Order $order){
        if($order->status === 'completed' || $order->status === 'cancelled' || $order->status === 'pending') {
        session()->flash('error','You cannot update status of completed, cancelled or pending orders');
        return false;
        }
      $status= $request->validate([
        'status'=>'required|string|in:pending,processing,shipped,completed,cancelled'
      ]);
      
      $order->update(['status'=>$status['status']]);
      session()->flash('success','Order status updated successfully');
      return true;
    }
    //export orders csv
    public function orderCsv(){
        $fileName = 'orders.csv';
      $orders = $this->order->with('user')->get();
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
}
