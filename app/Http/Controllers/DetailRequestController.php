<?php

namespace App\Http\Controllers;

use App\DetailRequest;
use Illuminate\Http\Request;

class DetailRequestController extends Controller
{
    public function index()
    {
        return DetailRequest::all();
    }
    public function show (DetailRequest $detail)
    {
        return $detail;
    }
    public function store(Request $request)
    {
        $detail = DetailRequest::create($request->all());
        return response()->json($detail, 201);
    }
    public function update(Request $request, DetailRequest $detail)
    {
        $detail->update($request->all());
        return response()->json($detail, 200);
    }
    public function delete(DetailRequest $detail)
    {
        $detail->delete();
        return response()->json(null, 204);
    }
}
