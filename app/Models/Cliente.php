<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nome', 'categoria_nome', 'tipo_doc', 'documento',
        'cep', 'rua', 'cidade', 'bairro', 'uf',
        'email', 'telefone', 'celular', 'obs', 'status', 'usuario_log'
    ];

    public function ordens() {
        return $this->hasMany(Ordem::class, 'cliente_id');
    }

    public function historicos() {
        return $this->hasMany(Historico::class, 'cliente_id');
    }
}
