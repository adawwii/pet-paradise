<?php

namespace App\Services;

use App\Models\Carts;
use App\Models\Product;

class CartService
{
    protected $carts;
    /**
     * Create a new class instance.
     */
    public function __construct(Carts $carts)
    {
        $this->carts = $carts;
    }

    //add and update cart
    public function addCart(Product $product){

        $user=auth()->user();
       //check the user has a cart
       $cart=$user->cart ?? $this->carts->create([
        'user_id'=>$user->id
       ]);
       //check the product is active
       if(!$product->is_active){
        return $attempt=['error'=>'product is not available!'];
       }
       //check the product stock
       if($product->stock<=0){
        return $attempt=['error'=>'product is out of stock!'];
       }

       //check the user's cart has the selected item
       $item=$cart->cartItems()->where('product_id',$product->id)->first();
       //check if the item's quantity applied
       if(request()->has('quantity')) {
           $item_quantity=request('quantity');
        if($item) {
            $item_quantity=$item->quantity + $item_quantity;
        $item->update([
            'quantity'=>$item_quantity
        ]);
       } else {
        $cart->cartItems()->create([
            'product_id'=>$product->id,
            'quantity'=>$item_quantity
        ]);
       }
       } else {

       if($item) {
        $item->increment('quantity');
       } else {
        $cart->cartItems()->create([
            'product_id'=>$product->id,
            'quantity'=>1
        ]);
       }
       
       }
       return $attempt=[
        'error'=>false,
        'success'=>true
       ];
    }
    //show user's cart
    public function showCart($cart){

        //check products in cart are active and have enough stock using map function
        $deletedItems= $cart->cartItems()
        ->whereHas('product', function ($query) {
            $query->where('is_active', false)
            ->orWhereColumn('stock', '<', 'cart_items.quantity');
            })
        ->delete();
        if($deletedItems>0){
            session()->flash('info','some items removed from your cart due to stock changes!');
        }
        $cart->load('cartItems.product');
        return $cart;
    }
}
