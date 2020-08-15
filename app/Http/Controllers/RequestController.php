<?php

namespace App\Http\Controllers;
use App\Request as aRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        return aRequest::all();
    }
    public function show (aRequest $request)
    {
        return $request;
    }
    public function store(Request $request)
    {
        $request1 = aRequest::create($request->all());
        return response()->json($request1, 201);
    }
    public function update (Request $request, aRequest $arequest)
    {
        $arequest->update($request->all());
        return response()->json($arequest, 200);
    }
    public function delete(aRequest $request)
    {
        $request->delete();
        return response()->json(null, 204);
    }
}
