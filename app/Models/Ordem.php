<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordem extends Model
{
    protected $table = 'ordens';

    protected $fillable = ['cliente_id', 'cliente_nome', 'status', 'usuario_log'];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function itens() {
        return $this->hasMany(ItemOrdem::class, 'ordem_id');
    }

    // Atributo virtual para pegar o total
    public function getTotalAttribute() {
        return $this->itens->sum(function($item) {
            return $item->quantidade * $item->preco;
        });
    }
}
