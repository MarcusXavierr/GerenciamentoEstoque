<?php

namespace App\Http\Controllers;

use App\Helpers\SaveStockMovementReport;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockMovementMvcController extends Controller
{
    private SaveStockMovementReport $saveReport;

    public function __construct()
    {
        $this->saveReport = new SaveStockMovementReport();
        $this->middleware('auth');
    }


    public function showAddProductPage($id)
    {
        $product = Product::findOrFail($id);
        return view('stock_movements.add_stock', compact('product'));
    }

    public function addProduct(Request $request, $id)
    {
        $data = $request->all();
        $stock = Stock::findOrFail($id);

        $isUpdated = $stock->update([
            'products_in_stock' => $data['quantity'] + $stock->products_in_stock
        ]);

        if (!$isUpdated) {
            return 'Error';
        }
        $this->saveReport->saveReportOfMovementOnDB($stock->product_id, $stock->id, $data['quantity'], false);
        return 'Success : ' . $stock->products_in_stock;
    }

    public function showRemoveProductPage($id)
    {
        $product = Product::findOrFail($id);
        return view('stock_movements.remove_stock', compact('product'));
    }

    public function removeProduct(Request $request, $id)
    {
        $data = $request->all();
        $stock = Stock::findOrFail($id);
        if ($stock->products_in_stock < $data['quantity']) {
            notify()
                ->error(
                    'Não foi possivel dar baixar nesse estoque porque não há produtos o bastante',
                    "Erro ao dar baixa em produto"
                );
            return redirect()->route('stock.remove.show', ['id' => $id]);
        }
        $isUpdated = $stock->update([
            'products_in_stock' => $stock->products_in_stock - $data['quantity']
        ]);

        if (!$isUpdated) {
            return 'Error';
        }

        $this->saveReport->saveReportOfMovementOnDB($stock->product_id, $stock->id, -$data['quantity'], false);
        return 'Success : ' . $stock->products_in_stock;
    }
}
