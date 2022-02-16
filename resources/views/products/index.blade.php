@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Listagem de produtos</h2>
        <a class="btn btn-success" href="{{route('product.create')}}">Criar produto</a>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>SKU</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->name}}</td>
                        <td>{{$product->SKU}}</td>
                        <td>{{$product->price}}</td>
                        <td>{{$product->stock->products_in_stock}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection