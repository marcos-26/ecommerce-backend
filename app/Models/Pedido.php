<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'total', 'frete', 'status', 'cep', 'endereco'
    ];

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
