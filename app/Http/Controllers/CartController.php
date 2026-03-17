<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
// use App\Models\Carts;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService){
        $this->cartService = $cartService;
    }
    //add to card
    public function add(Product $product) {
        
       $attempt=$this->cartService->addCart($product);

       return $attempt['error']
          ?  back()->with('error',$attempt['error'])
          :  back()->with('success','product added to cart');


        return back()->with('success','product added to cart!');
    }
    //show cart
    public function show() {
        $user=auth()->user();
        $cart=$user->cart;
        if (!$cart) {
        return view('cart.index',compact('cart'));
        }
        $response = Gate::inspect('view',$cart);
        if ($response->denied()) {
            return redirect()->route('home')->with('error',$response->message());
            }
        $cart=$this->cartService->showCart($cart);
        return view('cart.index',compact('cart'));
    }
    //update cart item
    public function update(Request $request , CartItem $cartItem) {
        $cart=$cartItem->cart;
        $response = Gate::inspect('update',$cart);
        if ($response->denied()) {
            return back()->with('error',$response->message());
        }
        if(blank($request->quantity)||$request->quantity < 1){
            return back()->with('info','minimum quantity is one!');
        }
      $quantity=['quantity'=>$request->quantity];
      $cartItem->update($quantity);
       return back();
    }
    //delete cart item
    public function remove(CartItem $cartItem){
        $cart=$cartItem->cart;
        $response = Gate::inspect('delete',$cart);
        if ($response->denied()) {
            return back()->with('error',$response->message());
        }
        $cartItem->delete();
        $cart->load('cartItems');
        if($cart->cartItems->count()>=1){
            return back()->with('info','item removed!');
            }
            $cart->delete();
            return back()->with('info','item removed!');
        }
}
