<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();
            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => 'не удалось получить заказы'], 500);
        }


    }


    public function store(Request $request)
    {
        try {
            // Получаем ID текущего аутентифицированного пользователя
            $userId = Auth::id();

            // Создаем заказ для текущего пользователя
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'total_amount' => 0, // Начальное значение
                // Другие поля заказа
            ]);
            if (!$order) {
                return response()->json(['error' => 'Не удалось создать заказ!!'], 500);
            }

            // Получаем товары из корзины текущего пользователя
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Корзина пользователя пуста'], 400);
            }

            // Добавляем товары из корзины в заказ
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $quantityInCart = $cartItem->total_items;

                $itemTotal=$product->price * $quantityInCart;

                $order->products()->attach($product->id, [
                    'quantity' => $quantityInCart,
                    'item_total'=>$itemTotal,
                ]);


                // Обновляем общую сумму заказа
                $order->total_amount += ($product->price * $quantityInCart);
            }

            // Сохраняем общую сумму заказа после добавления товаров
            $order->save();

            // Очищаем корзину после создания заказа
//            Cart::where('user_id', $userId)->delete();

            return response()->json(['message' => 'Заказ успешно создан', 'order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось создать заказ'], 500);
        }
    }


    public function updateOrderStatus(Request $request, $order_id)
    {


    }


    public function delete($order_id)
    {

    }


    public function show($order_id)
    {

    }

    public function getOrderTotal($order_id)
    {

    }
}
