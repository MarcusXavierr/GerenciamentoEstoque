<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockMovementMvcController extends Controller
{
    public function showAddProductPage($id)
    {
        $product = Product::findOrFail($id);
        return view('stock_movements.add_stock', compact('product'));
    }
}
