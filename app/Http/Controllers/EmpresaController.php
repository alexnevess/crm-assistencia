<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function create()
    {
        return view('empresa.empresa-create');
    }

    public function showFuncionarios()
    {
        return view('empresa.empresa-funcionarios');
    }

    public function store(Request $request)
    {
        //validação do nome
        $validaEmpresa = $request->validate([
            'nome' => 'required|string|max:255|unique:empresas,nome',
        ]);

        //Dados do usuário autenticado
        $user = Auth::user();

        //Adiciona o user_id ao array de validação
        $validaEmpresa['user_id'] = $user->id;

        //Salva a nova empresa no banco de dados e salva os dados na variável $empresa para adicionar id a tabela user
        $empresa = Empresa::create($validaEmpresa);

        //Salva o id da empresa criada na tabela do usuário que criou
        $user->empresa_id = $empresa->id;
        $user->save();

        return view('dashboard');
    }

}
