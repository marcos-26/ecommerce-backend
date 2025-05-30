<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id', 'variacao_id', 'quantidade', 'preco_unitario'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }
}
