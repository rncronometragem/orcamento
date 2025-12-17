<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clientes = Cliente::query()
            ->with(['enderecos', 'contatos'])

            ->when($search, function ($query, $search) {
                $query->where('nome', 'like', "%{$search}%")
                    ->orWhere('documento', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Clientes/Novo', []);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:20',
            'tipo_pessoa' => 'required|in:fisica,juridica',

            'enderecos' => 'required|array|min:1',
            'enderecos.*.cep' => 'nullable|string|max:10',
            'enderecos.*.logradouro' => 'required|string|max:255',
            'enderecos.*.numero' => 'nullable|string|max:20',
            'enderecos.*.bairro' => 'nullable|string|max:100',
            'enderecos.*.cidade' => 'required|string|max:100',
            'enderecos.*.uf' => 'required|string|size:2',

            'contatos' => 'array', // Opcional, mas se vier, valida dentro
            'contatos.*.departamento' => 'nullable|string|max:100',
            'contatos.*.email' => 'nullable|email|max:255',
            'contatos.*.celular' => 'required|string|max:20',
        ], [
            'enderecos.required' => 'É necessário cadastrar pelo menos um endereço.',
            'enderecos.*.logradouro.required' => 'O nome da rua é obrigatório em todos os endereços.',
            'enderecos.*.uf.size' => 'O estado (UF) deve ter 2 letras.',
        ]);

        DB::transaction(function () use ($dados) {

            $cliente = Cliente::create([
                'nome' => $dados['nome'],
                'documento' => $dados['documento'] ?? null,
                'tipo_pessoa' => $dados['tipo_pessoa'],
            ]);

            $cliente->enderecos()->createMany($dados['enderecos']);

            if (!empty($dados['contatos'])) {
                $cliente->contatos()->createMany($dados['contatos']);
            }
        });

        return redirect()->route('clientes.index')->with('message', 'Cliente cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $cliente = Cliente::with(['enderecos', 'contatos'])->findOrFail($id);

        return Inertia::render('Clientes/Editar', [
            'cliente' => $cliente
        ]);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:20',
            'tipo_pessoa' => 'required|in:fisica,juridica',

            'enderecos' => 'required|array|min:1',
            'enderecos.*.id' => 'nullable|integer',
            'enderecos.*.cep' => 'nullable|string|max:10',
            'enderecos.*.logradouro' => 'required|string|max:255',
            'enderecos.*.numero' => 'nullable|string|max:20',
            'enderecos.*.bairro' => 'nullable|string|max:100',
            'enderecos.*.cidade' => 'required|string|max:100',
            'enderecos.*.uf' => 'required|string|size:2',

            'contatos' => 'array',
            'contatos.*.id' => 'nullable|integer',
            'contatos.*.departamento' => 'nullable|string|max:100',
            'contatos.*.email' => 'nullable|email|max:255',
            'contatos.*.celular' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($cliente, $dados) {

            // 1. Atualiza dados principais
            $cliente->update([
                'nome' => $dados['nome'],
                'documento' => $dados['documento'] ?? null,
                'tipo_pessoa' => $dados['tipo_pessoa'],
            ]);

            $enderecosEnviadosIds = collect($dados['enderecos'])->pluck('id')->filter()->toArray();

            $cliente->enderecos()->whereNotIn('id', $enderecosEnviadosIds)->delete();

            foreach ($dados['enderecos'] as $end) {
                $cliente->enderecos()->updateOrCreate(
                    ['id' => $end['id'] ?? null],
                    $end
                );
            }

            $contatosEnviadosIds = collect($dados['contatos'] ?? [])->pluck('id')->filter()->toArray();
            $cliente->contatos()->whereNotIn('id', $contatosEnviadosIds)->delete();

            if (!empty($dados['contatos'])) {
                foreach ($dados['contatos'] as $ctt) {
                    $cliente->contatos()->updateOrCreate(
                        ['id' => $ctt['id'] ?? null],
                        $ctt
                    );
                }
            }
        });

        return redirect()->route('clientes.index')->with('message', 'Cliente atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('message', 'Cliente removido!');
    }
}
