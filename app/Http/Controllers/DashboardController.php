<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::where('user_cadastro_id', Auth::id())->count();
        $orcamentosAbertos = Orcamento::
            where('status', 'pendente')
            ->where('user_id', Auth::id())
            ->count();
        $faturamentoMes = Orcamento::where('status', 'aprovado')
            ->where('user_id', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->sum('valor_total');

        $vendasSemestrais = Orcamento::select(
            DB::raw('sum(valor_total) as total'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes")
        )
            ->where('status', 'aprovado')
            ->where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $ultimosOrcamentos = Orcamento::with('cliente')
            ->where("user_id", Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($orcamento) {
                return [
                    'id' => $orcamento->id,
                    'cliente' => $orcamento->cliente->nome,
                    'data' => $orcamento->created_at->format('d/m/Y'),
                    'valor' => 'R$ ' . number_format($orcamento->valor_total, 2, ',', '.'),
                    'status' => ucfirst($orcamento->status),
                ];
            });

        $orcamentosAprovados = Orcamento::select(['data_evento'])->where('status', 'like', 'aprovado')->get();

        $orcamentosPendentes = Orcamento::select(['data_evento'])->where('status', 'like', 'pendente')->get();

        $clientes = Cliente::select('id', 'nome')->orderBy('nome')->get();
        $produtos = Produto::select('id', 'nome', 'valor')->orderBy('nome')->get();

        return Inertia::render('Dashboard', [
            'kpis' => [
                'clientes' => $totalClientes,
                'orcamentos_abertos' => $orcamentosAbertos,
                'faturamento' => 'R$ ' . number_format($faturamentoMes, 2, ',', '.'),
            ],
            'grafico' => [
                'labels' => $vendasSemestrais->pluck('mes'),
                'dados' => $vendasSemestrais->pluck('total'),
            ],
            'recentes' => $ultimosOrcamentos,
            'clientes' => $clientes,
            'produtos' => $produtos,
            'orcamentosAprovados' => $orcamentosAprovados,
            'orcamentosPendentes' => $orcamentosPendentes,
        ]);
    }
}
