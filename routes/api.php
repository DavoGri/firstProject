<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);

Route::post('/products', [ProductController::class, 'store']);

Route::get('/products/{product_id}', [ProductController::class, 'show']);

Route::put('/products/{product_id}', [ProductController::class, 'update']);

Route::delete('/products/{product_id}', [ProductController::class, 'delete']);

Route::get('/products/category/{category_id}', [ProductController::class, 'filterByCategory']);




Route::get('/categories', [CategoryController::class, 'index']);

Route::post('/categories', [CategoryController::class, 'store']);

Route::get('/categories/{category_id}/products', [CategoryController::class, 'getProductByCategory']);

Route::put('/categories/{category_id}', [CategoryController::class, 'update']);

Route::delete('/categories/{category_id}', [CategoryController::class, 'delete']);





Route::post('/users/register',[UserController::class,'registerUser']);

Route::post('/users/login',[UserController::class,'loginUser']);

Route::get('/users/{user_id}',[UserController::class,'show']);

Route::put('/users/{user_id}',[UserController::class,'update']);

Route::delete('/users/{user_id}',[UserController::class,'delete']);






Route::get('/cart', [CartController::class, 'showCart']);

Route::post('/cart/add/{product_id}', [CartController::class, 'addToCart']);

Route::put('cart/update/{product_id}',[CartController::class,'updateProductFromCart']);

Route::delete('/cart/remove/{product_id}', [CartController::class, 'removeFromCart']);

Route::delete('/cart/clear', [CartController::class, 'clear']);




Route::get('/orders', [OrderController::class, 'index']);

Route::post('/orders', [OrderController::class, 'store']);

Route::get('/orders/{order_id}', [OrderController::class, 'show']);

Route::put('/orders/{order_id}/status', [OrderController::class, 'updateOrderStatus']);

Route::delete('/orders/{order_id}', [OrderController::class, 'delete']);

Route::get('/orders/{order_id}/total', [OrderController::class, 'getOrderTotal']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
