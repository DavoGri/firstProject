<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function showCart($product_id)
    {
       $cart = Cart::findOrFail($product_id);
       return response()->json($cart);
    }


    public function addToCart(Request $request,$product_id)
    { dd(1);
        $product = Product::find ($product_id);
        if (!$product){
            return response()->json(['message' => 'товар не найден']);
        }
        $cart=Cart::find(3);
        $cart->user();

    }


    public function updateProductFromCart(Request $request, $product_id)
    {


    }


    public function removeFromCart($product_id)
    {

    }


    public function clear()
    {

    }

}
