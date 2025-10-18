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
        //view para a criação da empresa
        return view('empresa.empresa-create');
    }

    public function showFuncionarios()
    {
        $userAdmin = Auth::user();
        $empresa = $userAdmin->empresaAfiliada;

        if(!$empresa)
        {
            return view('dashboard');
        }
        
        $funcionarios = User::where('empresa_id', $empresa->id)
        ->where('id', '!=', $userAdmin->id)
        ->orderBy('name')
        ->get();

        return view('empresa.empresa-funcionarios', [
            'empresa' => $empresa,
            'funcionarios' => $funcionarios,
        ]);
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


    public function removerFuncionario(User $funcionario)
    {
        $userAdmin = Auth::user();

        if ($userAdmin->empresa_id !== $funcionario->empresa_id) {
            return redirect()->route('empresa.funcionarios')->with('error', 'Ação não autorizada.');
        }

        // Proibir o Admin de remover a si mesmo
        if ($userAdmin->id === $funcionario->id) {
            return redirect()->route('empresa.funcionarios')->with('error', 'Você não pode remover a si mesmo da empresa.');
        }

        $nomeFuncionario = $funcionario->name;
        $nomeEmpresa = $userAdmin->empresaAfiliada->nome;

        // Remove o vínculo: Limpa empresa_id e perfil_acesso
        $funcionario->empresa_id = null;
        $funcionario->perfil_acesso = null;
        $funcionario->save();

        return redirect()->route('empresa.funcionarios')->with('status', "O funcionário {$nomeFuncionario} foi removido da empresa {$nomeEmpresa}.");
    }

    public function updatePerfilFuncionario(Request $request, User $funcionario)
    {
        $userAdmin = Auth::user();

        if ($userAdmin->empresa_id !== $funcionario->empresa_id) {
            return redirect()->route('show.funcionarios')->with('error', 'Ação não autorizada.');
        }

        // Validação dos Dados
        $request->validate([
            // O perfil é obrigatório e deve ser um desses valores
            'perfil_acesso' => 'required|in:ADMIN,ATENDENTE,TECNICO', 
        ]);

        // Atualização do Perfil
        $funcionario->perfil_acesso = $request->perfil_acesso;
        
        // Proibição de rebaixar a si mesmo 
        if ($userAdmin->id === $funcionario->id && $request->perfil_acesso !== 'ADMIN') {
             // Reverte a alteração e exibe um erro
             $funcionario->perfil_acesso = 'ADMIN'; 
             $funcionario->save();
             return redirect()->route('empresa.funcionarios')->with('error', 'Você não pode rebaixar seu próprio perfil de administrador.');
        }

        $funcionario->save();

        // Redirecionamento com Status
        return redirect()->route('empresa.funcionarios')->with('status', "Perfil de {$funcionario->name} atualizado para {$funcionario->perfil_acesso} com sucesso.");
    }
}
