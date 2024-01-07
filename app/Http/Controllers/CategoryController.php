<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }


    public function store(CategoryRequest $request)
    {
        $categories = $request->toArray();

        Category::create($categories);

        return response()->json(['message' => 'категория успешно создана']);

    }


    public function update(CategoryRequest $request, $category_id)
    {
        $category = Category::findOrFail($category_id);
        $category->update($request->all());
        return response()->json(['message' => 'категория успешно обновлена']);
    }


    public function delete($category_id)
    {
        $category = Category::findOrFail($category_id);
        $category->delete();
        return response()->json(['message' => 'товар успешно удален']);
    }


    public function getProductByCategory($category_id)
    {

            $category = Category::where('id', $category_id)->first();

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $products = $category->products;

            return response()->json(['products' => $products], 200);

    }

}
