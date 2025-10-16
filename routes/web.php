<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Empresa;
use App\Http\Controllers\EmpresaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Rotas para registrar empresa
    Route::get('/empresa/registra', [EmpresaController::class, 'create'])->name('empresa.create');//Redireciona a view com formulário para cria a empresa
    Route::post('/empresa',[EmpresaController::class, 'store'])->name('empresa.store');//registra empresa atravé do controller

    //Rotas para atualizar informações da empresa
    Route::patch('/empresa/{empresa}', [EmpresaController::class, 'update'])->name('empresa.update');

    // Rotas para registrar funcionários na empresa
    Route::get('/empresa/lista/funcionarios', [EmpresaController::class, 'showFuncionarios'])->name('empresa.funcionarios'); //Redireciona para view com lista de funcionários
    Route::get('/empresa/funcionarios/adiciona', [EmpresaController::class, 'adicionaFuncionario'])->name('adiciona.funcionario');//Redireciona para a view com formulário para registrar funcionário
    Route::post('/empresa/funcionarios/registra', [EmpresaController::class, 'registraFuncionario'])->name('registra.funcionario');// Redireciona para o método de registro de funcionário 


});

require __DIR__.'/auth.php';




