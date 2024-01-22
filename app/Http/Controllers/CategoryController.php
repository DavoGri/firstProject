<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось получить категории'], 500);
        }
    }


    public function store(CategoryRequest $request)
    {
        try {
            $categories = $request->validated();

            Category::create($categories);

            return response()->json(['message' => 'категория успешно создана']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось создать категорию'], 500);
        }

    }


    public function update(CategoryRequest $request, $category_id)
    {
        try {
            $category = Category::find($category_id);

            if (!$category) {
                return response()->json(['error' => 'Категория не найдена'], 404);
            }
            $category->update($request->validated());
            return response()->json(['message' => 'категория успешно обновлена']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось обновить категорию'], 500);
        }
    }


    public function delete($category_id)
    {
        try {
            $category = Category::find($category_id);

            if (!$category) {
                return response()->json(['error' => 'Категория не найдена'], 404);
            }

            $category->delete();

            return response()->json(['message' => 'товар успешно удален']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось удалить категорию'], 500);
        }
    }


    public function getProductByCategory($category_id)
    {
        try {

            $category = Category::where('id', $category_id)->first();

            if (!$category) {
                return response()->json(['message' => 'категория не найдена'], 404);
            }

            $products = $category->products;

            return response()->json(['products' => $products], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось получить продукты по категории'], 500);
        }

    }

}
