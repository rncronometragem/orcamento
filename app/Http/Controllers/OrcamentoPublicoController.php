<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Orcamento;
use Carbon\Carbon;
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

        $empresa = Empresa::where("user_id", $orcamento->user_id)->first();

        return Inertia::render('Orcamentos/ExibeOrcamentoCliente', [
            'orcamento' => $orcamento,
            'empresa' => $empresa
        ]);
    }

    public function responder($hash, Request $request)
    {
        $orcamento = Orcamento::where("hash", $hash)->firstOrFail();

        $orcamento->status = $request->input("status");

        $orcamento->data_resposta = Carbon::now();

        $orcamento->save();
    }
}
