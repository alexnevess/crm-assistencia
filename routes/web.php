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

    //Rotas empresas
    Route::get('/empresa/registra', [EmpresaController::class, 'create'])->name('empresa.create');//Redireciona a view com formulário para cria a empresa
    Route::post('/empresa',[EmpresaController::class, 'store'])->name('empresa.store');//registra empresa atravé do controller
    Route::get('/empresa/funcionarios', [EmpresaController::class, 'showFuncionarios'])->name('empresa.funcionarios'); //Redireciona para view com o formulário para adicionar novo funcionário
    Route::get('/empresa/funcionarios/adicionar', [EmpresaController::class, 'adicionaFuncionario'])->name('adiciona.funcionario');
});

require __DIR__.'/auth.php';




