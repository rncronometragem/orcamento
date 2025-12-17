<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrdem extends Model
{
    protected $table = 'itens_ordem';

    protected $fillable = ['ordem_id', 'descricao', 'quantidade', 'preco'];

    public function ordem() {
        return $this->belongsTo(Ordem::class, 'ordem_id');
    }
}
