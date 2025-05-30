<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $fillable = ['codigo', 'desconto', 'validade', 'minimo'];

    public function isValid()
    {
        return $this->validade >= now();
    }
}
