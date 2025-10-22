<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Cliente;
use App\Models\OrdemServico;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\OrdemServicoController;
use App\Notifications\InvoicePaid;
use App\Http\Controllers\Middleware\IsAdmin;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [OrdemServicoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/historico', [OrdemServicoController::class, 'historicoIndex'])
    ->middleware(['auth', 'verified'])
    ->name('historico');

    //Rotas com middleware que garante que o usuário é ADMIN para acessar telas relacionadas ao usuário
Route::middleware(['auth', 'admin'])->group(function () {

    //Redireciona para view da lista de funcionários
    Route::get('/empresa/lista/funcionarios', [EmpresaController::class, 'showFuncionarios'])->name('empresa.funcionarios'); 

    //Redireciona para a view com formulário para registrar funcionário
    Route::get('/empresa/funcionarios/adiciona', [EmpresaController::class, 'adicionaFuncionario'])->name('adiciona.funcionario');

    //Remover funcionário
    Route::delete('/empresa/funcionarios/{funcionario}', [EmpresaController::class, 'removerFuncionario'])->name('funcionario.remover');

    // Redireciona para o método de registro de funcionário em EmpresaController
    Route::post('/empresa/funcionarios/registra', [EmpresaController::class, 'registraFuncionario'])->name('registra.funcionario');
    
    //Atualiza perfil do funcionário
    Route::put('/funcionario/{funcionario}', [EmpresaController::class, 'updatePerfilFuncionario'])->name('funcionario.update.perfil');
});

Route::middleware('auth')->group(function () {
    //config perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //ROTAS PARA REGISTRAR EMPRESA

    //Redireciona a view com formulário para registrar a empresa
    Route::get('/empresa/registra', [EmpresaController::class, 'create'])->name('empresa.create');

    //registra empresa atravé do método store de EmpresaCOntroller
    Route::post('/empresa',[EmpresaController::class, 'store'])->name('empresa.store');

    //Atualizar empresa
    Route::patch('/empresa/{empresa}', [EmpresaController::class, 'update'])->name('empresa.update');

    //Rotas para ordem de serviço
    Route::resource('os', OrdemServicoController::class); //cria rotas como /os/create (formulário) e /os (POST para salvar)
});

require __DIR__.'/auth.php';




