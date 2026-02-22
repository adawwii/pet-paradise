<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Carts;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    //add to card
    public function add(Product $product) {
        $response = Gate::inspect('create',Carts::class);
        if ($response->denied()) {
            return back()->with('error',$response->message());
        }
       $user=auth()->user();
       //check the user has a cart
       $cart=$user->cart ?? Carts::create([
        'user_id'=>$user->id
       ]);

       //check the user's cart has the selected item
       $item=$cart->cartItems()->where('product_id',$product->id)->first();

       if($item) {
        $item->increment('quantity');
       } else {
        $cart->cartItems()->create([
            'product_id'=>$product->id,
            'quantity'=>1
        ]);
       }


        return back()->with('success','product added to cart!');
    }
    //show cart
    public function show() {
        $user=auth()->user();
        $response = Gate::inspect('viewAny',Carts::class);
        if ($response->denied()) {
            return redirect()->route('home')->with('error',$response->message());
            }
            $cart=$user->cart;
        return view('cart.index',compact('cart'));
    }
    //update cart item
    public function update(Request $request , CartItem $cartItem) {
        $response = Gate::inspect('update',auth()->user()->cart);
        if ($response->denied()) {
            return back()->with('error',$response->message());
        }
        if(blank($request->quantity)||$request->quantity==0){
            return back()->with('info','minimum quantity is one!');
        }
      $quantity=['quantity'=>$request->quantity];
      $cartItem->update($quantity);
       return back();
    }
    //delete cart item
    public function remove(CartItem $cartItem){

        $response = Gate::inspect('delete',auth()->user()->cart);
        if ($response->denied()) {
            return back()->with('error',$response->message());
        }
        $cart=$cartItem->cart;
        $cartItem->delete();
        if($cart->cartItems->count()>=1){
            return back()->with('info','item removed!');
            }
            $cart->delete();
            return back()->with('info','item removed!');
        }
}
