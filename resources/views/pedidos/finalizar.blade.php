@extends('layouts.app')

@section('title', 'Finalizar Pedido')

@section('content')
<h1>Finalizar Pedido</h1>

<form action="{{ route('pedidos.concluir') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>CEP</label>
        <input type="text" name="cep" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Endereço</label>
        <input type="text" name="endereco" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Aplicar Cupom (opcional)</label>
        <input type="text" name="cupom" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
</form>
@endsection
