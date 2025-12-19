<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    public function index()
    {
        // Pega a primeira empresa cadastrada ou null
        $empresa = Empresa::where("user_id", )->first();

        return Inertia::render('Empresa/Edit', [
            'empresa' => $empresa
        ]);
    }

    public function edit()
    {
        // Pega a primeira empresa cadastrada ou null
        $empresa = Empresa::first();

        return Inertia::render('Empresa/Edit', [
            'empresa' => $empresa
        ]);
    }

    public function update(Request $request)
    {
        // Validação dos campos da migration
        $data = $request->validate([
            'nome' => 'nullable|string|max:200',
            'cnpj' => 'nullable|string|max:100',
            'insc_estadual' => 'nullable|string|max:200',
            'cep' => 'nullable|string|max:100',
            'rua' => 'nullable|string|max:100',
            'numero' => 'nullable|string|max:200',
            'complemento' => 'nullable|string|max:150',
            'cidade' => 'nullable|string|max:25',
            'bairro' => 'nullable|string|max:25',
            'uf' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:250',
            'telefone' => 'nullable|string|max:200',
            'celular' => 'nullable|string|max:100',
            'site' => 'nullable|string|max:100',
            'cor_barra' => 'nullable|string|max:20',
            'cor_texto' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Busca a empresa existente ou cria uma nova instância
        $empresa = Empresa::first() ?? new Empresa();

        $empresa->user_id = Auth::user()->id;

        // Preenche os dados de texto (remove 'logo' da lista para tratar separado)
        $empresa->fill(collect($data)->except(['logo'])->toArray());

        // Tratamento da Logo
        if ($request->hasFile('logo')) {
            // Se já existir logo antiga, apaga do disco
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }

            // Salva na pasta 'logos' dentro do disco public
            $path = $request->file('logo')->store('logos', 'public');
            $empresa->logo = $path;
        }

        $empresa->save();

        return redirect()->back()->with('success', 'Dados da empresa salvos com sucesso!');
    }
}
