@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Estoque de produtos</h2>
        <table class="table table-bordered table-responsive mt-3">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Estoque Atual</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->name}}</td>
                        
                        <td>{{$product->stock->products_in_stock}}</td>
                        <td>
                            <div class="btn-group flex-wrap">
                                <a 
                                href="{{route('stock.add.show', 
                                ['id' => $product->stock->id])}}"
                                class="btn btn-outline-success">Adicionar estoque</a>

                                <a 
                                href="{{route('stock.remove.show', 
                                ['id' => $product->stock->id])}}"
                                class="btn btn-outline-danger">Dar baixa em produtos</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$products->links()}}
    </div>
@endsection