<?php

namespace App\Http\Controllers;

use App\Helpers\SaveStockMovementReport;
use App\Http\Requests\StockMovementFormRequest;
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


    public function index()
    {
        $products = Product::paginate(25);
        return view('stock_movements.index', compact('products'));
    }



    public function showAddProductPage($id)
    {
        $product = Product::findOrFail($id);
        return view('stock_movements.add_stock', compact('product'));
    }

    public function addProduct(StockMovementFormRequest $request, $id)
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
        notify()->success('Estoque adicionado com sucesso', 'Tudo ok');
        return redirect()->route('stock.index');
    }

    public function showRemoveProductPage($id)
    {
        $product = Product::findOrFail($id);
        return view('stock_movements.remove_stock', compact('product'));
    }

    public function removeProduct(StockMovementFormRequest $request, $id)
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
        notify()->success('Você deu baixa nos produtos com sucesso', 'Tudo ok');
        return redirect()->route('stock.index');
    }
}
