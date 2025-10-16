<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    public function adicionaFuncionario()
    {
        return view('empresa.empresa-addFuncionario');
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
        $user->perfil_acesso = 'ADMIN';
        $user->save();

        return view('dashboard');
    }

      public function registraFuncionario(Request $request)
    {
        // Valida dados do usuário para registro na empresa
        $request->validate([
            'email' => 'required|email|max:255',
            'perfil_acesso' => 'nullable|in:ADMIN,ATENDENTE,TECNICO', 
            // Adicionar validação de telefone se estiver sendo submetido
            'telefone' => 'nullable|telefone_com_ddd|max:15', 
        ]);
        
        $userAdmin = Auth::user();
        $empresa = $userAdmin->empresaAfiliada;

        // Garante que o administrador tem uma empresa vinculada
        if ($empresa === null) {
            return redirect()->route('dashboard')->with('error', 'Você deve ter uma empresa ativa para adicionar funcionários.');
        }

        // Busca o funcionário pelo email
        $funcionario = User::where('email', $request->email)->first();

        // Se o usuário não existe, redireciona com erro (seu fluxo atual não cria o usuário)
        if (!$funcionario) {
             return redirect()->route('adiciona.funcionario')->withErrors([
                'email' => 'Nenhum usuário encontrado com este e-mail. Peça ao funcionário para se cadastrar primeiro.',
            ])->onlyInput('email');
        }

        // Verifica se o funcionário já está vinculado a alguma empresa
        if ($funcionario->empresa_id !== null && $funcionario->empresa_id !== $empresa->id) {
             return redirect()->route('adiciona.funcionario')->withErrors([
                'email' => 'Este usuário já está vinculado a outra empresa.',
            ])->onlyInput('email');
        }
        
        // Atualiza o funcionário com o vínculo e perfil
        $funcionario->empresa_id = $empresa->id;
        
        // Se o perfil_acesso foi fornecido (não é null), atribui.
        // Se for null, o Eloquent atribui NULL (o que é permitido na sua migration).
        $funcionario->perfil_acesso = $request->perfil_acesso; 

        // Limpa o telefone antes de salvar, se existir
        if ($request->telefone) {
            $funcionario->telefone = preg_replace('/[^0-9]/', '', $request->telefone);
        }

        $funcionario->save();

        return redirect()->route('adiciona.funcionario')->with('status', "Funcionário {$funcionario->name} adicionado com sucesso à empresa {$empresa->nome}.");
    }

}
