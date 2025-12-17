<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ConfiguracaoController extends Controller
{
    // 1. Exibe a tela com os dados do usuário logado
    public function index()
    {
        return Inertia::render('Configuracoes/Index', [
            'user' => Auth::user()
        ]);
    }

    // 2. Processa a atualização do perfil
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            // Garante que o email é único, mas ignora o ID do próprio usuário
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed', // 'confirmed' exige o campo password_confirmation
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Só altera a senha se o usuário digitou algo no campo
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('message', 'Perfil atualizado com sucesso!');
    }
}
