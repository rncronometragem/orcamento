<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoItem;
use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class OrcamentoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orcamentos = Orcamento::query()
            ->with('cliente') // Carrega o nome do cliente
            ->when($search, function ($query, $search) {
                // Busca pelo ID do orçamento ou nome do cliente
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
            // 1. Cria o Cabeçalho
            $orcamento = Orcamento::create([
                'cliente_id' => $dados['cliente_id'],
                'status' => $dados['status'],
                'observacoes' => $dados['observacoes'],
                'valor_total' => 0 // Será calculado abaixo
            ]);

            $totalGeral = 0;

            // 2. Cria os Itens
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

            // 3. Atualiza o total
            $orcamento->update(['valor_total' => $totalGeral]);
        });

        return redirect()->route('orcamentos.index')->with('message', 'Orçamento gerado com sucesso!');
    }

    public function edit($id)
    {
        $orcamento = Orcamento::with('itens')->findOrFail($id);

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
                'valor_total' => $totalGeral
            ]);
        });

        return redirect()->route('orcamentos.index')->with('message', 'Orçamento atualizado!');
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
        ], [
            'itens.required' => 'Adicione pelo menos um item ao orçamento.',
        ]);
    }
}
