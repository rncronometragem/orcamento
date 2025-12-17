<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Produto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CARDS DO TOPO (KPIs)
        $totalClientes = Cliente::count();
        $orcamentosAbertos = Orcamento::where('status', 'pendente')->count();
        $faturamentoMes = Orcamento::where('status', 'aprovado')
            ->whereMonth('created_at', now()->month)
            ->sum('valor_total');

        // 2. GRÁFICO: Vendas dos últimos 6 meses
        // Agrupa por mês e soma o valor dos aprovados
        $vendasSemestrais = Orcamento::select(
            DB::raw('sum(valor_total) as total'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes")
        )
            ->where('status', 'aprovado')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // 3. TABELA: Últimos 5 Orçamentos
        $ultimosOrcamentos = Orcamento::with('cliente')
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
            'recentes' => $ultimosOrcamentos
        ]);
    }
}
