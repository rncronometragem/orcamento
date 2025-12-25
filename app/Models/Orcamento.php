<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $fillable = [
        'cliente_id',
        'status',
        'valor_total',
        'observacoes',
        'hash',
        'user_id',
        'data_reposta',
        'data_expiracao',
        'pode_ver_unitario',
        'data_evento',
        'local_evento',
    ];

    // Um orçamento pertence a um cliente
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    // Um orçamento tem vários itens
    public function itens() {
        return $this->hasMany(OrcamentoItem::class);
    }
}
