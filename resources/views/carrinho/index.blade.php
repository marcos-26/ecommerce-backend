@extends('layouts.app')

@section('title', 'Carrinho')

@section('content')
<h1>Carrinho</h1>

@if(count($carrinho) > 0)
<table class="table">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
    @foreach($carrinho as $item)
        <tr>
            <td>{{ $item['nome'] }}</td>
            <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
            <td>{{ $item['quantidade'] }}</td>
            <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p><strong>Frete:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p>
<p><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>

<a href="{{ route('pedidos.finalizar') }}" class="btn btn-success">Finalizar Pedido</a>
@else
<p>Seu carrinho está vazio.</p>
@endif
@endsection
