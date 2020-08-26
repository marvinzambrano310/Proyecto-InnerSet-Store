<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource ;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    private static $messages = [
        'required'=>'El campo :attribute es obligatorio',
        'string'=>'El campo :attribute tiene que ser un string',
    ];
    public function index()
    {
        return new CategoryCollection(Category::all());
    }
    public function show (Category $category)
    {
        $this->authorize('view', $category);
        return response()->json(new CategoryResource($category), 200);
    }
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $request->validate([
            'name' => 'required|string',
        ], self::$messages);

        $category = new Category($request->all());
        $category->save();
        return response()->json(new CategoryResource($category), 201);
    }
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $request->validate([
            'name' => 'required|string',
        ], self::$messages);
        $category->update($request->all());
        return response()->json($category, 200);
    }
    public function delete(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json(null, 204);
    }
}
