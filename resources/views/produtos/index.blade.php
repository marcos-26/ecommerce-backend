@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<h1>Cadastro e Gerenciamento de Produtos</h1>

@if(session('sucesso'))
    <div class="alert alert-success">
        {{ session('sucesso') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Formulário de Produto --}}
<h3>Novo Produto</h3>
<form method="POST" action="{{ route('produtos.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Preço</label>
        <input type="number" step="0.01" name="preco" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Estoque do Produto</label>
        <input type="number" name="estoque" class="form-control" required value="0">
    </div>

    <h5>Variações (opcional)</h5>
    <div id="variacoes"></div>
    <button type="button" class="btn btn-secondary btn-sm" onclick="adicionarVariacao()">Adicionar Variação</button>

    <br><br>
    <button type="submit" class="btn btn-success">Cadastrar Produto</button>
</form>

<hr>

{{-- Lista de Produtos --}}
<h2>Produtos Cadastrados</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Variações</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    @foreach($produtos as $produto)
        <tr>
            <td>{{ $produto->nome }}</td>
            <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
            <td>
                @if($produto->variacoes->count())
                    <ul>
                        @foreach($produto->variacoes as $variacao)
                            <li>{{ $variacao->nome }} ({{ $variacao->estoque->quantidade ?? 0 }})</li>
                        @endforeach
                    </ul>
                @else
                    Nenhuma
                @endif
            </td>
            <td>{{ $produto->estoque->quantidade ?? 0 }}</td>
            <td>
                {{-- Form editar --}}
                <form action="{{ route('produtos.update', $produto->id) }}" method="POST" class="mb-1">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-column gap-1">
                        <input type="text" name="nome" value="{{ $produto->nome }}" class="form-control" required>
                        <input type="number" step="0.01" name="preco" value="{{ $produto->preco }}" class="form-control" required>
                        <input type="number" name="estoque" value="{{ $produto->estoque->quantidade ?? 0 }}" class="form-control" required>
                        <button class="btn btn-warning btn-sm">Atualizar</button>
                    </div>
                </form>

                {{-- Comprar --}}
                <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm w-100">Comprar</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<hr>

{{-- Carrinho --}}
<h2>Carrinho</h2>
@if(session('carrinho') && count(session('carrinho')) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @php
            $subtotal = 0;
        @endphp
        @foreach(session('carrinho') as $item)
            @php
                $subtotal += $item['subtotal'];
            @endphp
            <tr>
                <td>{{ $item['nome'] }}</td>
                <td>{{ $item['quantidade'] }}</td>
                <td>R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
                <td>
                    <form action="{{ route('carrinho.remover', $item['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Remover</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>

    {{-- Cálculo do Frete --}}
    @php
        if($subtotal >= 200){
            $frete = 0;
        } elseif($subtotal >= 52 && $subtotal <= 166.59){
            $frete = 15;
        } else {
            $frete = 20;
        }
        $total = $subtotal + $frete;
    @endphp

    <p><strong>Frete:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p>
    <p><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>

    {{-- CEP --}}
    <div class="mb-3">
        <label>CEP para entrega</label>
        <input type="text" id="cep" class="form-control" placeholder="Digite o CEP">
        <div id="endereco" class="mt-2"></div>
    </div>
@else
    <p>Carrinho vazio.</p>
@endif

@if(session('carrinho') && count(session('carrinho')) > 0)
    <h2>Finalizar Compra</h2>

    <button type="button" class="btn btn-success" onclick="finalizarCompra()">Finalizar Compra</button>

    <div id="mensagem-compra" class="alert alert-success mt-3 d-none">
        Compra realizada com sucesso!
    </div>
@endif

<script>
    function adicionarVariacao() {
        const container = document.getElementById('variacoes');
        const index = container.children.length;

        const div = document.createElement('div');
        div.classList.add('mb-2');
        div.innerHTML = `
            <div class="d-flex gap-2">
                <input type="text" name="variacoes[${index}][nome]" class="form-control" placeholder="Nome da Variação" required>
                <input type="number" name="variacoes[${index}][estoque]" class="form-control" placeholder="Estoque" value="0" required>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentNode.parentNode.remove()">Remover</button>
            </div>
        `;
        container.appendChild(div);
    }

    // Consulta CEP
    document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        document.getElementById('endereco').innerHTML = `<span class="text-danger">CEP não encontrado.</span>`;
                    } else {
                        document.getElementById('endereco').innerHTML = `
                            <strong>Endereço:</strong> ${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}
                        `;
                    }
                })
                .catch(() => {
                    document.getElementById('endereco').innerHTML = `<span class="text-danger">Erro ao consultar o CEP.</span>`;
                });
        } else {
            document.getElementById('endereco').innerHTML = '';
        }
    });

     function finalizarCompra() {

        document.getElementById('mensagem-compra').classList.remove('d-none');
    }
</script>
@endsection
