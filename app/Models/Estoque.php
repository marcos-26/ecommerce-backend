<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = ['variacao_id', 'quantidade'];

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }
}
