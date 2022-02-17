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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->name}}</td>
                        <td>{{$product->SKU}}</td>
                        <td>R$ {{number_format($product->price, 2, ',', '.')}}</td>
                        <td>{{$product->stock->products_in_stock}}</td>
                        <td>
                            <div class="btn-group flex-wrap">
                                <a class="btn btn-outline-primary btn-sm" href="{{route('product.edit', ['product' => $product->id])}}">Editar</a>
                                <x-delete-item :item="$product" :route="route('product.destroy', ['product' => $product->id])"/>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection