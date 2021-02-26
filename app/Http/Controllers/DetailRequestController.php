<?php

namespace App\Http\Controllers;

use App\DetailRequest;
use App\Http\Resources\RequestCollection;
use Illuminate\Http\Request;
use App\Http\Resources\DetailRequest as DetailResource ;
use App\Http\Resources\DetailRequestCollection;

class DetailRequestController extends Controller
{
    private static $messages = [
        'required'=>'El campo :attribute es obligatorio',
        'exists'=>'El parámetro :attribute no corresponde a ningun registro',
        'integer'=>'El parámetro ingresado en :attribute no es un entero',
        'numeric'=>'El parámetro ingresado en :attribute no es un número',
    ];
    public function index(\App\Request $request)
    {
        return response()->json(new DetailRequestCollection($request->detail), 200);
    }
    public function show (DetailRequest $detail)
    {

        return response()->json(new DetailResource ($detail), 200);
    }
    public function store(Request $request, \App\Request $arequest)
    {
        $request->validate([
            //'request_id' => 'required|exists:requests,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'final_price' => 'required|numeric',
        ], self::$messages);
        $detail = $arequest->detail()->save(new DetailRequest($request->all()));
        return response()->json(new DetailResource($detail), 201);
    }
    public function update(Request $request, DetailRequest $detail)
    {
       /* $detail->update($request->all());
        return response()->json($detail, 200); */
    }
    public function delete(DetailRequest $detail)
    {
        /*$detail->delete();
        return response()->json(null, 204);*/
    }
}
