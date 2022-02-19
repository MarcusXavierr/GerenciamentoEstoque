<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $relatory = DB::table('stock_movements')
            ->join('products', 'products.SKU', 'stock_movements.product_id')
            ->whereDate('stock_movements.created_at', now())
            ->orderBy('stock_movements.created_at', 'desc')->get();
        return view('home', compact('relatory'));
    }
}
