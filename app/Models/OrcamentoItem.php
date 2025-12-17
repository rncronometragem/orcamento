<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrcamentoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcamento_id',
        'descricao',
        'quantidade',
        'preco_unitario',
        'subtotal'
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }
}
