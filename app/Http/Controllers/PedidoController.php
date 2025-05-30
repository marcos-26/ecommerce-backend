<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Variacao;
use App\Models\Cupom;
use App\Mail\PedidoRealizado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function finalizar(Request $request)
    {
        $carrinho = session()->get('carrinho', []);
        if (empty($carrinho)) {
            return back()->with('error', 'Carrinho vazio!');
        }

        $subtotal = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);

        // Frete
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20;
        }

        // Cupom
        $desconto = 0;
        if ($request->cupom) {
            $cupom = Cupom::where('codigo', $request->cupom)->first();
            if ($cupom && $cupom->isValid() && $subtotal >= $cupom->minimo) {
                $desconto = $cupom->desconto;
            }
        }

        $total = $subtotal + $frete - $desconto;

        $pedido = Pedido::create([
            'total' => $total,
            'frete' => $frete,
            'status' => 'Pendente',
            'cep' => $request->cep,
            'endereco' => $request->endereco,
        ]);

        foreach ($carrinho as $id => $item) {
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'variacao_id' => $id,
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
            ]);

            $variacao = Variacao::find($id);
            $variacao->estoque->decrement('quantidade', $item['quantidade']);
        }

        // Envio de email
        Mail::to($request->email)->send(new PedidoRealizado($pedido));

        session()->forget('carrinho');

        return redirect()->route('produtos.index')->with('success', 'Pedido realizado com sucesso!');
    }
}
