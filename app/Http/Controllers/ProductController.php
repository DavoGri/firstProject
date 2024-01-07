<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }


    public function store(ProductRequest $request)
    {
        $product = $request->toArray();

        Product::create($product);

        return response()->json(['message' => 'товар успешно создан']);
    }


    public function update(ProductRequest $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'stock_quantity' => $request->input('stock_quantity'),
            'category_id' => $request->input('category_id'),
        ]);

        return response()->json(['message' => 'данные успешно обновлены']);
    }


    public function delete($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->delete();
        return response()->json(['message' => 'товар успешно удален']);
    }


    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);

        return response()->json($product);
    }

    public function filterByCategory($category_id)
    {
        $products = Product::where('category_id',$category_id)->get();


        return response()->json($products);
    }
}