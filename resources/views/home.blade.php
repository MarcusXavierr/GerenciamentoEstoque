@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Seja bem vindo(a) de volta ao sistema de gerenciamento de estoque</h3>
    <p>Veja abaixo o relatório de hoje sobre de produtos adicionados no estoque e de baixas nos produtos</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th class="col-1">Quantidade</th>
                <th class="col-2">Sistema usado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($relatory as $stock)
                <tr>
                    <td>{{$stock->name}}</td>
                    <td>
                        @if($stock->products_movemented > 0)
                            <span style="color:green">
                                <i class="fal fa-caret-up"></i>
                                {{$stock->products_movemented}}
                            </span>
                        @else
                            <span style="color:red">
                                <i class="fal fa-caret-down"></i>
                                {{$stock->products_movemented}}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($stock->is_api)
                            API
                        @else
                            Sistema interno
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
