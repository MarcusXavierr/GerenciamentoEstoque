<?php

namespace App\Http\Controllers;

use App\Helpers\SaveStockMovementReport;
use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class StockMovementApiController extends Controller
{

    private array $productNotFoundObject = [
        'message' => 'product_id not found',
    ];

    private array $errorValidating = [
        "message" => 'You did not fill in all the required fields.'
    ];

    private SaveStockMovementReport $saveReport;

    public function __construct()
    {
        $this->saveReport = new SaveStockMovementReport();
    }

    public function index()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $stock = $product->stock;
            $product['stock'] = $stock->products_in_stock;
        }
        return $products;
    }



    /**
     * @bodyParam id int required The id of the product. Example: 9
     * @bodyParam quantity int required The number of products tha you want to add in stock. Example: 50  
     */

    public function addStock(Request $request)
    {
        //get validated data
        $data = $this->getData($request);
        if ($data == null) {
            return response($this->errorValidating, 400);
        }
        //get product and stock intances
        $response = $this->getProductAndStock($data);
        if ($response == null) {
            return response($this->productNotFoundObject, 404);
        }
        //Try to update product, and if not, return status code 401
        $isUpdated = $response['stock']->update([
            'products_in_stock' => $response['stock']->products_in_stock + $data['quantity']
        ]);
        if (!$isUpdated) {
            return response(['response' => 'Error while updating stock, please try later'], 400);
        }
        $this->saveReport
            ->saveReportOfMovementOnDB($response['product']->SKU, $response['stock']->id, $data['quantity'], true);

        return response([
            'stock' => Stock::find($response['stock']->id),
            'message' => 'Stock updated successfully'
        ], 201);
    }

    /**
     * @bodyParam id int required The id of the product. Example: 7
     * @bodyParam quantity int required The number of products tha you want to remove from stock. Example: 50  
     */
    public function removeStock(Request $request)
    {
        //get validated data
        $data = $this->getData($request);
        if ($data == null) {
            return response($this->errorValidating, 400);
        }

        //get product and stock intances
        $response = $this->getProductAndStock($data);
        if ($response == null) {
            return response($this->productNotFoundObject, 404);
        }

        //checks if there are enough products to be removed
        if ($response['products_in_stock'] < $data['quantity']) {
            return response(['message' => 'you are trying to remove more products than there are'], 401);
        }

        //Try to update product, and if not, return status code 401
        $isUpdated = $response['stock']->update([
            'products_in_stock' => $response['products_in_stock'] - $data['quantity']
        ]);
        if (!$isUpdated) {
            return response(['message' => 'Error while updating stock, please try later'], 401);
        }

        //Save stock movement report
        $this->saveReport
            ->saveReportOfMovementOnDB($response['product']->SKU, $response['stock']->id, -$data['quantity'], true);

        return response([
            'stock' => Stock::find($response['stock']->id),
            'message' => 'Stock updated successfully'
        ], 201);
    }


    /**
     * This method returns an array with the instance of the user model, the stock model and the amount of products in stock.
     * Or it returns null when an error happens
     */
    private function getProductAndStock(array $data): array | null
    {
        $product = Product::find($data['id']);
        if ($product == null) {
            return null;
        }

        $stock = $product->stock;
        $actualStock = $stock->products_in_stock;
        return [
            'product' => $product,
            'stock' => $stock,
            'products_in_stock' => $actualStock
        ];
    }

    /**
     * Return an array with request data, or returns null when an error happens
     */
    private function getData(Request $request): array | null
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'quantity' => 'required'
        ]);
        if ($validator->fails()) {
            return null;
        }

        $data = $validator->validated();
        return $data;
    }
}
