<?php

namespace App\Http\Controllers;

use App\DetailRequest;
use App\Http\Resources\RequestCollection;
use Illuminate\Http\Request;
use App\Http\Resources\DetailRequest as DetailResource ;
use App\Http\Resources\DetailRequestCollection;

class DetailRequestController extends Controller
{
    public function index()
    {
        return new DetailRequestCollection(DetailRequest::all());
    }
    public function show (DetailRequest $detail)
    {
        return response()->json(new DetailRequest($detail), 200);
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
