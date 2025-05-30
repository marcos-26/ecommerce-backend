<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('variacoes.estoque')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer|min:0',
            'variacoes.*.nome' => 'nullable|string',
            'variacoes.*.estoque' => 'nullable|integer|min:0',
        ]);

        $produto = Produto::create([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);

        // Estoque do produto (sem variações)
        Estoque::create([
            'produto_id' => $produto->id,
            'quantidade' => $request->estoque,
        ]);

        // Variações (se houver)
        if ($request->has('variacoes')) {
            foreach ($request->variacoes as $variacao) {
                if (!empty($variacao['nome'])) {
                    $novaVariacao = $produto->variacoes()->create([
                        'nome' => $variacao['nome'],
                    ]);

                    Estoque::create([
                        'produto_id' => $produto->id,
                        'variacao_id' => $novaVariacao->id,
                        'quantidade' => $variacao['estoque'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('produtos.index')->with('sucesso', 'Produto cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $produto = Produto::with('variacoes.estoque')->findOrFail($id);
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer|min:0',
        ]);

        $produto->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);

        $produto->estoque->update([
            'quantidade' => $request->estoque,
        ]);

        return back()->with('sucesso', 'Produto atualizado com sucesso!');
    }

}
