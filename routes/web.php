<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\OrcamentoPublicoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rota pública usando um hash ou uuid
Route::get('/proposta/{hash}', [OrcamentoPublicoController::class, 'show'])->name('orcamentos.publico');
// Rota para o cliente aprovar/rejeitar
Route::post('/proposta/{hash}/responder', [OrcamentoPublicoController::class, 'responder'])->name('orcamentos.responder');

Route::post('/orcamentos/responder/{hash}', [OrcamentoPublicoController::class, 'responder'])->name('orcamentos.responder');


Route::middleware('auth')->group(function () {

    Route::prefix('/clientes')->name('clientes.')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->name('index');
        Route::get('/novo', [ClienteController::class, 'create'])->name('create');
        Route::post('', [ClienteController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [ClienteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ClienteController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClienteController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('produtos')->name('produtos.')->group(function () {
        Route::get('/', [ProdutoController::class, 'index'])->name('index');
        Route::get('/novo', [ProdutoController::class, 'create'])->name('create');
        Route::post('/', [ProdutoController::class, 'store'])->name('store');
        Route::get('/{produto}/editar', [ProdutoController::class, 'edit'])->name('edit');
        Route::put('/{produto}', [ProdutoController::class, 'update'])->name('update');
        Route::delete('/{produto}', [ProdutoController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('orcamentos')->name('orcamentos.')->group(function () {
        Route::get('/', [OrcamentoController::class, 'index'])->name('index');
        Route::get('/novo', [OrcamentoController::class, 'create'])->name('create');
        Route::get('/{id}', [OrcamentoController::class, 'show'])->name('show');
        Route::post('/', [OrcamentoController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [OrcamentoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OrcamentoController::class, 'update'])->name('update');
        Route::delete('/{id}', [OrcamentoController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('configuracoes')->name('configuracoes.')->group(function () {

        Route::get('/', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
        Route::post('/', [ConfiguracaoController::class, 'update'])->name('configuracoes.update');

       Route::get('/migrate', function () {
           Artisan::call('migrate', ['--force' => true]);

           return '<h1>Migrations rodadas com sucesso!</h1><pre>' . Artisan::output() . '</pre>';
       })->name('index');

       Route::get('migrate-rollback', function () {
           Artisan::call('migrate:rollback');

           return '<pre>' . Artisan::output() . '</pre>';
       })->name('rollback');

        Route::get('/limpar-cache', function() {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            return 'Cache de views e rotas limpo!';
        });

        Route::get('/link-storage', function() {
            Artisan::call('storage:link');
            return '<pre>' . Artisan::output() . '</pre>';
        });

        Route::get('/ziggy-generate', function() {
            Artisan::call('ziggy:generate');
            return 'Ziggy gerado com sucesso!';
        });
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('orcamentos', App\Http\Controllers\OrcamentoController::class);

    Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.index');

    Route::post('/empresa', [EmpresaController::class, 'update'])->name('empresa.update');
});
