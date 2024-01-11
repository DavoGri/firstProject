<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    public function index()
    {
        try {
            $products = Product::all();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }


    public function store(ProductRequest $request)
    {
        try {
            $product = $request->toArray();

            Product::create($product);

            return response()->json(['message' => 'товар успешно создан']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Ошибка валидации: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }


    public function update(ProductRequest $request, $product_id)
    {
        try {
            $product = Product::findOrFail($product_id);

            $product->update([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
                'stock_quantity' => $request->input('stock_quantity'),
                'category_id' => $request->input('category_id'),
            ]);

            return response()->json(['message' => 'данные успешно обновлены']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Ошибка валидации: ' . $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Товар не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }


    public function delete($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
            $product->delete();
            return response()->json(['message' => 'товар успешно удален']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Товар не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }


    public function show($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);

            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Товар не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }

    public function filterByCategory($category_id)
    {
        try {
            $products = Product::where('category_id', $category_id)->get();

            return response()->json($products);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Что-то пошло не так'], 500);
        }
    }
}