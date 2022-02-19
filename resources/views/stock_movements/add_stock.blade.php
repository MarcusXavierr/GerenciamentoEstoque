@extends('layouts.app')


@section('content')

    <div class="container">
        <h2>Adicionar estoque de produtos</h2> 
       <div class="text-center">
            <h4 class="mt-3">{{$product->name}}</h4>
            <p>{{$product->description}}</p>
            <p>Preço: R${{number_format($product->price, 2, ',', '.')}}</p>
            <p>Estoque atual: {{$product->stock->products_in_stock}}</p>
       </div>
        <form action="{{route('stock.add', ['id' => $product->stock->id])}}" method="post">
            @csrf
            <div class="input-group">
                <input type="number" name="quantity" class="form-control">
                <button class="btn btn-outline-primary" >Enviar</button>
            </div>
            
        </form>
    </div>
@endsection