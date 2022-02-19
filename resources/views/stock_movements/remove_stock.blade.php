@extends('layouts.app')


@section('content')

    <div class="container">
        <h2>Dar baixa no estoque do produto</h2> 
       <div class="text-center">
            <h4 class="mt-3">{{$product->name}}</h4>
            <p>{{$product->description}}</p>
            <p>Preço: R${{number_format($product->price, 2, ',', '.')}}</p>
            <p>Estoque atual: {{$product->stock->products_in_stock}}</p>
       </div>
        <form action="{{route('stock.remove', ['id' => $product->stock->id])}}" method="post">
            @csrf
            <div class="input-group">
                <input type="number" name="quantity" class="form-control">
                <button class="btn btn-outline-primary" >Enviar</button>
            </div>
            @if ($errors->has('quantity'))
                <span class="help-block text-sm text-danger ">
                    {{ $errors->first('quantity') }}
                </span>
            @endif
        </form>
    </div>
@endsection