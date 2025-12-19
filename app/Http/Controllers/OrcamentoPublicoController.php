<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrcamentoPublicoController extends Controller
{
    public function show($hash)
    {
        // Buscando pelo UUID para segurança
        $orcamento = Orcamento::where('hash', $hash)
            ->with(['cliente', 'itens']) // Carrega relacionamentos
            ->firstOrFail();

        return Inertia::render('Orcamentos/ExibeOrcamentoCliente', [
            'orcamento' => $orcamento,
            'empresa' => [ // Dados da sua empresa (pode vir do config ou banco)
                'nome' => 'Sua Empresa Tech',
                'logo' => null,
                'email' => 'contato@suaempresa.com',
                'telefone' => '(11) 99999-9999'
            ]
        ]);
    }
}
