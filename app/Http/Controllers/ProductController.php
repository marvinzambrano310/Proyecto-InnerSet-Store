<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource ;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    private static $messages = [
        'required'=>'El campo :attribute es obligatorio',
        'exists'=>'El parámetro :attribute no corresponde a ningun registro',
        'integer'=>'El parámetro ingresado en :attribute no es un entero',
        'numeric'=>'El parámetro ingresado en :attribute no es un número',
        'string'=>'El campo :attribute tiene que ser un string',
        'image'=>'El campo :attribute no es una imagen',
    ];
    public function index()
    {
        return new ProductCollection(Product::all());
    }
    public function show (Product $product)
    {
        return response()->json(new ProductResource($product), 200);
    }
    public function searchProduct($name)
    {
        $products = Product::search("%$name*%")->get();
        return response()->json(new ProductCollection($products), 200);
    }

    public function image(Product $product)
    {
        return response()->download(public_path(Storage::url($product->image)), $product->name);
    }

    public function store(Request $request)
    {
        //$this->authorize('create', Article::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|dimensions:min_width=200,min_height=200',
        ], self::$messages);

        $product = new Product($request->all());
        $path = $request->image->store('public/products');
        $product->image = 'products/' . basename($path);
        $product->setAttribute('name', $request->get('name'));
        $product->save();

        return response()->json(new ProductResource($product), 201);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json($product, 200);
    }
    public function delete(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
