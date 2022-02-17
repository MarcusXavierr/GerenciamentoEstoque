@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar produto</h2>
        <form action="{{route('product.update', ['product' => $product->id])}}" method="post">
                @csrf
                @method('PUT')
                <div class="mt-2">
                    <label for="productName" class="form-label">Nome do produto</label>
                    <input type="text" name="name" class="form-control" value="{{$product->name}}">
                    @if ($errors->has('name'))
                        <span class="help-block text-sm text-danger ">
                            {{ $errors->first('name') }}
                        </span>
                    @endif
                </div>
                <div class="mt-2">
                    <label for="productPrice" class="form-label">Preço</label>
                    <input type="text" name="price" id="productPrice" class="form-control" value="{{$product->price}}">
                    @if ($errors->has('price'))
                        <span class="help-block text-sm text-danger ">
                            {{ $errors->first('price') }}
                        </span>
                    @endif
                </div>
                <div class="mt-2">
                    <label for="productDescription" class="form-label">Descrição do produto</label>
                    <input type="text" name="description" class="form-control" value="{{$product->description}}">
                    @if ($errors->has('description'))
                        <span class="help-block text-sm text-danger ">
                            {{ $errors->first('description') }}
                        </span>
                    @endif
                </div> 
                
                
                <button class="btn btn-primary mt-3">Editar produto</button>
                
        </form>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vanilla-masker@1.1.1/build/vanilla-masker.min.js"></script>
<script>
    VMasker(document.querySelector("#productPrice")).maskMoney({
    precision: 2,
    separator: ',',
    delimiter: '.',
}); 
</script>
@endsection