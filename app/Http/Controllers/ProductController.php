<?php

namespace App\Http\Controllers;

use App\Helpers\TreatStringInput;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    protected TreatStringInput   $treatString;

    public function __construct()
    {
        $this->treatString = new TreatStringInput();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all(['id', 'SKU', 'price', 'description', 'name']);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['price'] = $this->treatString->convertStringToFloat($data['price']);
        if ($this->checkForDuplicatedProduct($data['SKU'])) {
            return redirect()->route('product.create');
        }

        $success = $this->tryToCreateProduct($data);
        if (!$success) {
            return 'Error ao criar produto no banco de dados';
        }

        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * returns true if there are duplicates
     */
    private function checkForDuplicatedProduct(string $sku): bool
    {
        $result = DB::table('products')->selectRaw('count(*) AS counter')
            ->where('SKU', $sku)->first();

        return $result->counter != 0;
    }

    private function tryToCreateProduct(array $data): bool
    {
        $isCreated = true;
        //I'm going to try to create a product, 
        //and then I'm going to create a stock table for that product
        try {
            $product = new Product();
            $product = $product->create($data);
            $product->stock()->create(['products_in_stock' => 0]);
        } catch (Exception $exception) {
            $isCreated = false;
        }

        return $isCreated;
    }
}
