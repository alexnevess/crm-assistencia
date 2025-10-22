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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/notification/{osId}/{clienteId}', function ($osId, $clienteId){
//     $cliente = Cliente::find($clienteId);
//     $ordemServico = OrdemServico::find($osId);
//     $cliente->notify(new OsStatusNotification($cliente, $ordemServico));
// })->name('notification');

Route::middleware('auth')->group(function () {
    //config perfil
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

    //Remover funcionário
    Route::delete('/empresa/funcionarios/{funcionario}', [EmpresaController::class, 'removerFuncionario'])->name('funcionario.remover');

    //Atualiza perfil do funcionário
    Route::put('/funcionario/{funcionario}', [EmpresaController::class, 'updatePerfilFuncionario'])->name('funcionario.update.perfil');

    //Rotas ordem de serviço
    Route::resource('os', OrdemServicoController::class); //cria rotas como /os/create (formulário) e /os (POST para salvar)
});

require __DIR__.'/auth.php';




