<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function create()
    {
        return view('empresa.create');
    }

    public function store(Request $request)
    {
        //validação do nome
        $validateData = $request->validate([
            'nome' => 'required|string|max:255|unique:empresas,nome',
        ]);


        $user = Auth::user();
        //Adiciona o user_id do usuário logado
        $validateData['user_id'] = $user->id;
        $empresa = Empresa::create($validateData);

        $user->empresa_id = $empresa->id;
        $user->save();

        return view('empresa.create');

    }
}
