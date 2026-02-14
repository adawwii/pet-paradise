<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //add to card
    public function add(Product $product) {
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
        $cart=auth()->user()->cart;
        return view('cart.index',compact('cart'));
    }
    //update cart item
    public function update(Request $request , CartItem $cartItem) {
        if(blank($request->quantity)||$request->quantity==0){
            return back()->with('info','minimum quantity is one!');
        }
        $quantity=['quantity'=>$request->quantity];
        $cartItem->update($quantity);
        return back();
    }
    //delete cart item
    public function remove(CartItem $cartItem){
        $cart=$cartItem->cart;
        $cartItem->delete();
        if($cart->cartItems->count()>=1){
            return back()->with('info','item removed!');
            }
            $cart->delete();
            return back()->with('info','item removed!');
        }
}
