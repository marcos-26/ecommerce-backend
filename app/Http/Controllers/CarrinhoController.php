<?php

namespace App\Http\Controllers;

use App\Models\Produto;

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinho = session()->get('carrinho', []);
        return view('carrinho.index', compact('carrinho'));
    }

    public function adicionar(Produto $produto)
    {
        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produto->id])) {
            $carrinho[$produto->id]['quantidade']++;
            $carrinho[$produto->id]['subtotal'] += $produto->preco;
        } else {
            $carrinho[$produto->id] = [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'quantidade' => 1,
                'preco' => $produto->preco,
                'subtotal' => $produto->preco,
            ];
        }

        session()->put('carrinho', $carrinho);

        return back()->with('sucesso', 'Produto adicionado ao carrinho!');
    }

    
    public function remover($id)
    {
        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$id])) {
            unset($carrinho[$id]);
            session()->put('carrinho', $carrinho);
        }

        return back()->with('sucesso', 'Produto removido do carrinho.');
    }

    public function limpar()
    {
        session()->forget('carrinho');
        return redirect()->route('carrinho.index');
    }
}
