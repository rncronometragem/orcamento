<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Empresa extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela
    protected $table = 'empresas';

    protected $fillable = [
        'nome', 'cnpj', 'insc_estadual',
        'cep', 'rua', 'numero', 'complemento', 'cidade', 'bairro', 'uf',
        'email', 'telefone', 'celular', 'site',
        'logo', 'cor_barra', 'cor_texto'
    ];

    // Acessor para pegar a URL completa da logo no frontend
    // Uso no Vue: empresa.logo_url
    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute()
    {
        return $this->logo
            ? Storage::url($this->logo)
            : null;
    }
}
