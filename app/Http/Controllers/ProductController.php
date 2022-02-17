<?php

namespace App\Http\Controllers;

use App\Helpers\TreatStringInput;
use App\Http\Requests\ProductFormRequest;
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
        $this->middleware('auth');
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
    public function store(ProductFormRequest $request)
    {
        $data = $request->all();
        $data['price'] = $this->treatString->convertStringToFloat($data['price']);

        $success = $this->tryToSaveProductInDB($data);

        if (!$success) {
            notify()->error('Houve um erro no banco de dados, por favor tente mais terde novamente', 'Erro ao criar produto');
            return redirect()->route('product.create');
        }

        notify()->success("Produto criado com sucesso", 'Tudo ok');
        return redirect()->route('product.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
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
        $data = $request->all();

        $data['price'] = $this->treatString->convertStringToFloat($data['price']);
        $product = Product::findOrFail($id);

        try {
            $product->update($data);
        } catch (Exception) {
            notify()->error('Houve um erro no banco de dados, por favor tente mais terde novamente', 'Erro ao atualizar produto');
            return redirect()->route('product.edit', ['product' => $id]);
        }

        notify()->success("Produto atualizado com sucesso", 'Tudo ok');
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            $product->delete();
        } catch (Exception) {
            notify()->error('Houve um erro no banco de dados, por favor tente mais terde novamente', 'Erro ao deletar produto');
            return redirect()->route('product.edit', ['product' => $id]);
        }

        notify()->success("Produto deletado com sucesso", 'Tudo ok');
        return redirect()->route('product.index');
    }


    /**
     * returns true if there are duplicates
     */

    private function tryToSaveProductInDB(array $data): bool
    {
        $isCreated = true;
        //I'm going to try to create a product, 
        //and then I'm going to create a stock table for that product
        try {
            $product = new Product();
            $product = $product->create($data);
            $product->stock()->create(['products_in_stock' => 0]);
        } catch (Exception) {
            $isCreated = false;
        }

        return $isCreated;
    }
}
