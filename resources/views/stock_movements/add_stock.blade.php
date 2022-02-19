@extends('layouts.app')


@section('content')

    <div class="container">
       <div class="col-md-6">
        <div class="">
            <h3 class="mt-3">{{$product->name}}</h3>
            <p><strong>Descrição:</strong> {{$product->description}}</p>
            <p><strong>Preço:</strong> R${{number_format($product->price, 2, ',', '.')}}</p>
            <p><strong>Estoque atual:</strong> {{$product->stock->products_in_stock}}</p>
       </div>
        <form action="{{route('stock.add', ['id' => $product->stock->id])}}" method="post">
            @csrf
            <label for="">Digite a quantidade de produtos que deseja adicionar no estoque</label>
            <div class="input-group mt-2"> 
                <input type="number" name="quantity" class="form-control"> 
                <button class="btn btn-outline-success" >Adicionar</button>
            </div>
            @if ($errors->has('quantity'))
                <span class="help-block text-sm text-danger ">
                    {{ $errors->first('quantity') }}
                </span>
            @endif
            
        </form>
       </div>
    </div>
@endsection