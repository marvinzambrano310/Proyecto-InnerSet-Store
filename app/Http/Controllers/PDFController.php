<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Request as Requests;

class PDFController extends Controller
{
    public function PDFRequests(){
        $requests = DB::table('requests')
        ->select('user_id',
            DB::raw('MAX(date) as date'),
            DB::raw('SUM(subtotal) as subtotal'),
            DB::raw('SUM(surcharge) as surcharge'),
            DB::raw('SUM(total) as total'))
        ->groupBy('user_id')
         ->get();
        $users = User::all();
        $pdf = PDF::loadView('requests', compact('requests','users'));
        return $pdf->download('Pedidos.pdf');
    }
    public function PDFProducts(){
        $products = Product::all();
        $quantity = DB::table('detail_requests')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->get();
        $pdf = PDF::loadView('quantityProducts', compact('quantity','products'));
        return $pdf->download('Productos-mas-vendidos.pdf');
    }
}
