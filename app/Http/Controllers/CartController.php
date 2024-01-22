<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    public function showCart(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Пользователь не найден'], 404);
            }

            $cartItems = $user->cart()->with('product')->get();

            return response()->json(['cart_items' => $cartItems]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось получить содержимое корзины'], 500);
        }
    }


    public function addToCart(Request $request, $product_id, $quantity)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Пользователь не найден'], 404);
            }

            $product = Product::find($product_id);

            if (!$product) {
                return response()->json(['error' => 'Товар не найден'], 404);
            }

            // Получаем текущее количество товара в корзине для конкретного продукта пользователя
            $currentTotalItems = Cart::where('user_id', $user->id)
                ->where('product_id', $product_id)
                ->value('total_items');

            $newTotalItems = $currentTotalItems + $quantity;

            if ($newTotalItems > $product->stock_quantity) {
                return response()->json(['error' => 'Количество товара в корзине не может превышать общее количество на складе', 'stock_quantity' => $product->stock_quantity], 400);
            }

            $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $product_id)
                ->first();

            if ($cartItem) {
                // Обновляем существующий элемент корзины
                $cartItem->total_items += $quantity;
                $cartItem->total_price += ($product->price * $quantity);
                $cartItem->save();
            } else {
                // Создаем новый элемент корзины
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product_id,
                    'total_items' => $quantity,
                    'total_price' => ($product->price * $quantity),
                ]);
            }

            return response()->json(['message' => 'Товар успешно добавлен в корзину', 'stock_quantity' => $product->stock_quantity]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось добавить товар в корзину', 'details' => $e->getMessage()], 500);
        }
    }
    public function updateProductFromCart(CartRequest $request, $product_id)
    {
        try {
            $user = $request->user();
            $cartItem = Cart::where('product_id', $product_id)->where('user_id', $user->id)->firstOrFail();

            $this->authorize('update', $cartItem);

            DB::beginTransaction();

            try {
                $total_item = $request->input('total_item');
                $product = $cartItem->product;

                // Проверка, чтобы не превышать общее количество товара в корзине
                if ($total_item <= $product->stock_quantity) {
                    $cartItem->total_items = $total_item;
                    $cartItem->total_price = ($product->price * $total_item);
                    $cartItem->save();
                    DB::commit();

                    return response()->json(['message' => 'Товар в корзине обновлен успешно'], 200);
                } else {
                    DB::rollBack();
                    return response()->json(['message' => 'Количество товара в корзине не может превышать общее количество на складе'], 400);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Товар не найден в корзине'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'У вас нет прав для выполнения этого действия'], 403);
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении товара в корзине: ' . $e->getMessage());
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }
    public function removeFromCart($product_id)
    {
        try {

            $cartItem = Cart::where('product_id', $product_id)->firstOrFail();


            $this->authorize('delete', $cartItem);


            $cartItem->delete();

            return response()->json(['message' => 'Товар успешно удален из корзины'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'У вас нет прав для выполнения этого действия'], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Товар не найден в корзине'], 404);
        } catch (\Exception $e) {
            // Логгирование ошибки
            Log::error('Ошибка при удалении товара из корзины: ' . $e->getMessage());
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }


}
