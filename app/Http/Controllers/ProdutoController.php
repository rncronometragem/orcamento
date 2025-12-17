<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth; // Importante para pegar o usuário logado

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $produtos = Produto::query()
            ->when($search, function ($query, $search) {
                $query->where('nome', 'like', "%{$search}%");
            })
            ->orderBy('nome', 'asc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Produtos/Index', [
            'produtos' => $produtos,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Produtos/Form');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome'  => 'required|string|max:200',
            'valor' => 'nullable|numeric|min:0', // Aceita nulo conforme o banco
        ]);

        // Adiciona quem fez a alteração
        $dados['usuario_log'] = Auth::user()->name ?? 'Sistema';

        Produto::create($dados);

        return redirect()->route('produtos.index')->with('message', 'Produto criado com sucesso!');
    }

    public function edit(Produto $produto)
    {
        return Inertia::render('Produtos/Form', [
            'produto' => $produto
        ]);
    }

    public function update(Request $request, Produto $produto)
    {
        $dados = $request->validate([
            'nome'  => 'required|string|max:200',
            'valor' => 'nullable|numeric|min:0',
        ]);

        // Atualiza quem fez a última alteração
        $dados['usuario_log'] = Auth::user()->name ?? 'Sistema';

        $produto->update($dados);

        return redirect()->route('produtos.index')->with('message', 'Produto atualizado!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('message', 'Produto removido.');
    }
}
