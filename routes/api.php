<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/products', [ProductController::class, 'index']);

Route::post('/products', [ProductController::class, 'store'])->middleware('auth:api','admin');

Route::get('/products/{product_id}', [ProductController::class, 'show']);

Route::put('/products/{product_id}', [ProductController::class, 'update'])->middleware('auth:api','admin');

Route::delete('/products/{product_id}', [ProductController::class, 'delete'])->middleware('auth:api','admin');;

Route::get('/products/stock', [ProductController::class,'Stock']);




Route::get('/categories', [CategoryController::class, 'index']);

Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth:api','admin');

Route::get('/categories/{category_id}/products', [CategoryController::class, 'getProductByCategory']);

Route::put('/categories/{category_id}', [CategoryController::class, 'update'])->middleware('auth:api','admin');;

Route::delete('/categories/{category_id}', [CategoryController::class, 'delete'])->middleware('auth:api','admin');;





Route::get('/register',[RegisterController::class,'index']);

Route::post('/register',[RegisterController::class,'register']);

Route::get('/login',[LoginController::class,'index'])->name('login.index');

Route::post('/login',[LoginController::class,'login'])->name('login');

Route::post('/logout',[LoginController::class,'logout'])->middleware('auth:api');

Route::post('/create-super-admin',[SuperAdminController::class,'create']);

Route::post('/create-admin',[SuperAdminController::class,'createAdmin'])->middleware('auth:api');









Route::get('/users/{user_id}',[UserController::class,'show']);

Route::put('/users/{user_id}',[UserController::class,'update'])->middleware('auth:api');

Route::delete('/users/{user_id}',[UserController::class,'delete'])->middleware('auth:api');





Route::middleware('auth:api')->group(function (){

    Route::get('/cart', [CartController::class, 'showCart']);

    Route::post('/cart/add/{product_id}/{quantity}', [CartController::class, 'addToCart']);

    Route::put('cart/update/{product_id}',[CartController::class,'updateProductFromCart']);

    Route::delete('/cart/remove/{product_id}', [CartController::class, 'removeFromCart']);



});




Route::middleware('auth:api')->group(function (){

    Route::get('/orders', [OrderController::class, 'index'])->middleware('admin');

    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/orders/{order_id}', [OrderController::class, 'show']);

    Route::put('/orders/{order_id}/status', [OrderController::class, 'updateOrderStatus'])->middleware('admin');

    Route::delete('/orders/{order_id}', [OrderController::class, 'delete']);




});