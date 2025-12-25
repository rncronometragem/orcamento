<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoItem;
use App\Models\Cliente;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class OrcamentoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orcamentos = Orcamento::query()
            ->where("user_id", Auth::id())
            ->with('cliente')
            ->when($search, function ($query, $search) {
                $query->where('id', $search)
                    ->orWhereHas('cliente', function($q) use ($search) {
                        $q->where('nome', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Orcamentos/Index', [
            'orcamentos' => $orcamentos,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Orcamentos/Form', [
            'clientes' => Cliente::select('id', 'nome')->orderBy('nome')->get(),
            'produtos' => Produto::select('id', 'nome', 'valor')->orderBy('nome')->get(), // Para o autocomplete
        ]);
    }

    public function store(Request $request)
    {
        $dados = $this->validarOrcamento($request);

        DB::transaction(function () use ($dados) {
            $orcamento = Orcamento::create([
                'cliente_id' => $dados['cliente_id'],
                'status' => $dados['status'],
                'observacoes' => $dados['observacoes'],
                'user_id' => Auth::id(),
                'valor_total' => 0,
                'pode_ver_unitarios' => $dados['pode_ver_unitarios'],
                'data_evento' => $dados['data_evento'],
                'local_evento' => $dados['local_evento'],
            ]);

            $totalGeral = 0;

            foreach ($dados['itens'] as $item) {
                $subtotal = $item['quantidade'] * $item['preco_unitario'];
                $totalGeral += $subtotal;

                $orcamento->itens()->create([
                    'descricao' => $item['descricao'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco_unitario'],
                    'subtotal' => $subtotal
                ]);
            }

            $orcamento->update([
                'hash' => (string) Str::uuid(),
            ]);

            $orcamento->update(['valor_total' => $totalGeral]);
        });

        return redirect()->route('orcamentos.index')->with('message', 'Orçamento gerado com sucesso!');
    }

    public function edit($id)
    {
        $orcamento = Orcamento::with('itens')->findOrFail($id);

        if ($orcamento->data_evento)
        {
            $orcamento->data_evento = Carbon::create($orcamento->data_evento)->format('Y-m-d');
        }

        return Inertia::render('Orcamentos/Form', [
            'orcamento' => $orcamento,
            'clientes' => Cliente::select('id', 'nome')->orderBy('nome')->get(),
            'produtos' => Produto::select('id', 'nome', 'valor')->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $dados = $this->validarOrcamento($request);

        DB::transaction(function () use ($orcamento, $dados) {

            // 1. Sincroniza Itens (Remove os deletados, atualiza existentes, cria novos)
            $itensIdsEnviados = collect($dados['itens'])->pluck('id')->filter()->toArray();
            $orcamento->itens()->whereNotIn('id', $itensIdsEnviados)->delete();

            $totalGeral = 0;

            foreach ($dados['itens'] as $item) {
                $subtotal = $item['quantidade'] * $item['preco_unitario'];
                $totalGeral += $subtotal;

                $orcamento->itens()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'descricao' => $item['descricao'],
                        'quantidade' => $item['quantidade'],
                        'preco_unitario' => $item['preco_unitario'],
                        'subtotal' => $subtotal
                    ]
                );
            }

            // 2. Atualiza Cabeçalho e Total Recalculado
            $orcamento->update([
                'cliente_id' => $dados['cliente_id'],
                'status' => $dados['status'],
                'observacoes' => $dados['observacoes'],
                'valor_total' => $totalGeral,
                'pode_ver_unitarios' => $dados['pode_ver_unitarios'],
                'data_evento' => $dados['data_evento'],
                'local_evento' => $dados['local_evento'],
            ]);
        });

        return redirect()->route('orcamentos.index')->with('message', 'Orçamento atualizado!');
    }

    public function show($id)
    {
        $orcamento = Orcamento::with(['itens', 'cliente'])->findOrFail($id);
        return Inertia::render('Orcamentos/Show', [
            'orcamento' => $orcamento,
        ]);
    }

    public function destroy($id)
    {
        Orcamento::findOrFail($id)->delete();
        return redirect()->route('orcamentos.index')->with('message', 'Orçamento excluído.');
    }

    // Função auxiliar de validação para não repetir código
    private function validarOrcamento(Request $request)
    {
        return $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'status' => 'required|in:pendente,aprovado,rejeitado,cancelado',
            'observacoes' => 'nullable|string',
            'itens' => 'required|array|min:1',
            'itens.*.id' => 'nullable|integer',
            'itens.*.descricao' => 'required|string',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco_unitario' => 'required|numeric|min:0',
            'pode_ver_unitarios' => 'nullable|boolean',
            'data_evento' => 'nullable|date',
            'local_evento' => 'nullable|string',
        ], [
            'itens.required' => 'Adicione pelo menos um item ao orçamento.',
        ]);
    }
}
