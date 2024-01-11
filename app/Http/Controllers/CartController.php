<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function showCart($product_id)
    {
       $cart = Cart::findOrFail($product_id);
       return response()->json($cart);
    }


    public function addToCart($product_id,$quantity)
    {
        $user_id=Auth::id();


        $product = Product::find ($product_id);

        if (!$product){
            return response()->json(['message' => 'товар не найден']);
        }
        $cartItem = Cart::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();
        if ($cartItem) {

            $cartItem->total_items += $quantity;
            $cartItem->total_price += ($product->price * $quantity);
            $cartItem->save();
        } else {

            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'total_items' => $quantity,
                'total_price' => ($product->price * $quantity),

            ]);
        }

        return response()->json(['message' => 'Товар успешно добавлен в корзину']);

    }


    public function updateProductFromCart(Request $request, $product_id)
    {
        try {
            $this->validate($request, [
                'total_item' => 'required|numeric|min:1',
            ]);

            $total_item = $request->input('total_item');

            $cartItem = Cart::where('product_id', $product_id)->firstOrFail();


            $cartItem->total_items = $total_item;
            $cartItem->save();

            return response()->json(['message' => 'Товар в корзине обновлен успешно'], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Ошибка валидации: ' . $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Товар не найден в корзине'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }



    public function removeFromCart($product_id)
    {
        try {
        $cartItem = Cart::where('product_id', $product_id)->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Товар успешно удален из корзины'], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Товар не найден в корзине'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Что-то пошло не так'], 500);
    }
    }


    public function clear()
    {
        try {

            Cart::truncate();

            return response()->json(['message' => 'Корзина очищена'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }

}
