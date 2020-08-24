<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource ;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    public function index()
    {
        return new CategoryCollection(Category::all());
    }
    public function show (Category $category)
    {
        return response()->json(new CategoryResource($category), 200);
    }
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return response()->json($category, 200);
    }
    public function delete(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
