<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nome' => 'required|max:200',
            'documento' => 'nullable|unique:clientes,documento',
            'email' => 'nullable|email'
        ]);

        // Pega o usuário logado
        $validado['usuario_log'] = auth()->user()->name;

        Cliente::create($validado);

        return redirect()->route('clientes.index')->with('sucesso', 'Cliente cadastrado!');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return redirect()->back()->with('sucesso', 'Dados atualizados!');
    }
}
