<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        try {


            $categories = $request->input('category_id', []);

            // Фильтрация товаров по категориям и в наличии
            $productsQuery = Product::when(!empty($categories), function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            })
                ->where('stock_quantity', '>', 0)
                ->paginate(5);


            return ProductResource::collection($productsQuery);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так: ' . $e->getMessage()], 500);
        }
    }





    public function Stock()
    {
        try {

            $productsLowStock = Product::where('stock_quantity', '<', 5)->paginate(5);

            return ProductResource::collection($productsLowStock);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так: ' . $e->getMessage()], 500);
        }
    }





    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            $product = Product::create($data);

            $message = 'Товар успешно создан';
            $resource = new ProductResource($product);
            $resource->additional(['message' => $message]);

            return $resource;

        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(ProductUpdateRequest $request, $product_id)
    {
        try {
            $product = Product::findOrFail($product_id);


            $updateData = $request->only(['name', 'price', 'description', 'stock_quantity', 'category_id']);


            $product->update($updateData);

            $message = 'Товар успешно обновлен';
            $resource = new ProductResource($product);
            $resource->additional(['message' => $message]);

            return $resource;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Товар не найден.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось обновить товар.', 'details' => $e->getMessage()], 500);
        }
    }
    public function delete($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
            $product->delete();
            return response()->json(['message' => 'Товар успешно удален']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Товар не найден.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось удалить товар.', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
            return new ProductResource($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Товар не найден.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось найти товар.', 'details' => $e->getMessage()], 500);
        }
    }

}