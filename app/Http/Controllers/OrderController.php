<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            // Получаем идентификатор текущего пользователя
            $userId = Auth::id();

            // Получаем товары из корзины пользователя
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            // Проверяем, не пуста ли корзина пользователя
            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Корзина пользователя пуста'], 400);
            }

            // Создаем новый заказ
            $order = Order::create([
                'user_id' => $userId,
                'status' => Constants::ORDER_STATUS_PENDING,
                'total_amount' => 0,
            ]);

            // Проверяем, успешно ли создан заказ
            if (!$order) {
                return response()->json(['error' => 'Не удалось создать заказ!!'], 500);
            }

            // Итерируем по товарам в корзине
            foreach ($cartItems as $cartItem) {
                // Получаем информацию о товаре
                $product = $cartItem->product;
                // Получаем количество данного товара в корзине
                $quantityInCart = $cartItem->total_items;

                // Рассчитываем общую стоимость товара
                $itemTotal = $product->price * $quantityInCart;

                // Добавляем товар к заказу с указанием количества и общей стоимости
                $order->products()->attach($product->id, [
                    'quantity' => $quantityInCart,
                    'item_total' => $itemTotal,
                ]);

                // Обновляем общую стоимость заказа
                $order->total_amount += $itemTotal;
            }

            // Сохраняем общую стоимость заказа после добавления товаров
            $order->save();

            // Очищаем корзину пользователя после создания заказа
            Cart::where('user_id', $userId)->delete();

            // Возвращаем успешный ответ с информацией о заказе
            return response()->json(['message' => 'Заказ успешно создан', 'order' => $order]);
        } catch (\Exception $e) {
            // В случае возникновения исключения возвращаем ошибку
            return response()->json(['error' => 'Не удалось создать заказ'], 500);
        }
    }




        public function updateOrderStatus(Request $request, $orderId)
    {
        try {

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Заказ не найден'], 404);
            }

            // Обновляем статус заказа
            $newStatus = $request->input('status');
            $order->update(['status' => $newStatus]);

            return response()->json(['message' => 'Статус заказа успешно обновлен', 'order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось обновить статус заказа'], 500);
        }
    }



    public function delete($order_id)
    {

        $user = Auth::user();


        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }


        $order = $user->orders()->find($order_id);

        if (!$order) {
            return response()->json(['error' => 'Заказ не найден или вы не являетесь его владельцем'], 404);
        }


        $order->delete();

        return response()->json(['message' => 'Заказ успешно удален']);
    }


    public function show($order_id)
    {
        try {

            $user = Auth::user();



            $order = $user->orders()->find($order_id);

            if (!$order) {
                return response()->json(['error' => 'Заказ не найден или вы не являетесь его владельцем'], 404);
            }

            return response()->json(['order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось получить информацию о заказе'], 500);
        }
    }


}
