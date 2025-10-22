<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\OrdemServico;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use App\Notifications\OsStatusNotification;



class OrdemServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $ordensServico = collect();

        if($user->empresaAfiliada)
        {
            $empresaId = $user->empresaAfiliada->id;

            $ordensServico = OrdemServico::with('cliente', 'equipamento')
                             ->where('empresa_id', $empresaId)
                             ->where('arquivada', false)
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);
        }

        return view('dashboard', [
            'ordensServico' => $ordensServico,
        ]);
    }

    public function historicoIndex()
    {
        $user = Auth::user();
        $ordensServico = collect();

        if($user->empresaAfiliada)
        {
            $empresaId = $user->empresaAfiliada->id;

            $ordensServico = OrdemServico::with('cliente', 'equipamento')
                             ->where('empresa_id', $empresaId)
                             ->where('arquivada', true)
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);
        }

        return view('ordens_servico.arquivadas', [
            'ordensServico' => $ordensServico,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ordens_servico.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $usuarioId = auth()->id();

        $request->validate([

        //Regras de validação cliente
        'nome' => 'required|string|max:150',
        'telefone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:150',
        'cnpj_cpf' => 'nullable|string|max:14',

        //Regras de validação equipamento
        'equip_marca' => 'nullable|string|max:80',
        'equip_modelo' => 'required|string|max:80', 
        'equip_numero_serie' => 'nullable|string|max:120',

        // Regras de validação OS
        'problema_relato' => 'required|string',
        'prazo_previsto' => 'nullable|date',
        ], [
            // Mensagens de erro personalizadas
        'nome.required' => 'O nome do cliente é obrigatório.',
        'equip_modelo.required' => 'O modelo do equipamento é obrigatório.',
        'problema_relato.required' => 'O problema relatado é obrigatório.',
        ]);

        return DB::transaction(function () use ($request, $empresaId, $usuarioId) {

            // 1. GERAÇÃO DO NÚMERO DA OS (LÓGICA MOVIDA PARA O MODEL)
            $numeroOS = OrdemServico::generateNextNumero($empresaId);

            // 2. BUSCA/CRIA O CLIENTE (Dados do Cliente)
            $cliente = Cliente::firstOrNew([
                'cnpj_cpf' => $request->input('cnpj_cpf'),
                'empresa_id' => $empresaId,
            ]);
            
            // Preenche ou atualiza os demais dados do cliente
            $cliente->fill([
                'nome' => $request->input('nome'),
                'telefone' => $request->input('telefone'),
                'email' => $request->input('email'),
                'empresa_id' => $empresaId,
            ]);

            $cliente->save();

            // 3. CRIA EQUIPAMENTO (Dados do Equipamento)
            $equipamento = Equipamento::create([
                'empresa_id' => $empresaId,
                'cliente_id' => $cliente->id, // Vínculo com o Cliente
                
                // CORREÇÃO: Usando nomes de coluna do DB (que parece usar prefixo)
                'equip_marca' => $request->input('equip_marca'),
                'equip_modelo' => $request->input('equip_modelo'),
                'equip_numero_serie' => $request->input('equip_numero_serie'),
            ]);

            // 4. CRIA A ORDEM DE SERVIÇO (Dados da OS)
            $os = OrdemServico::create([
                'empresa_id' => $empresaId,
                'cliente_id' => $cliente->id,
                'equipamento_id' => $equipamento->id,
                
                // Usuários e Status
                'criador_user_id' => $usuarioId,
                'status' => 'ABERTA', 
                
                // Dados da OS
                'numero' => $numeroOS,
                'problema_relato' => $request->input('problema_relato'),
                'prazo_previsto' => $request->input('prazo_previsto'),
            ]);

            return redirect()->route('os.show', $os->id)
                            ->with('success', "Ordem de Serviço #{$os->numero} criada com sucesso!");
        });
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //Obtém id do usuário
        $empresaId = auth()->user()->empresa_id;

        // 2. Busca a Ordem de Serviço usando o ID da OS ($id) E o ID da Empresa ($empresaId).
        // Se a OS não for encontrada OU se ela pertencer a outra empresa, firstOrFail()
        // irá lançar uma exceção 404 (Not Found).
        $os = OrdemServico::with('cliente', 'equipamento', 'criador', 'atualizador')
            ->where('id', $id)
            ->where('empresa_id', $empresaId) 
            ->firstOrFail();
        
        return view('ordens_servico.show', compact('os'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:ABERTA,EM_ESPERA,AGUARDANDO_PEÇAS,EM_DIAGNOSTICO,EM_REPARO,PRONTA,CANCELADA',
    ]);

    $os = OrdemServico::findOrFail($id);
    $os->status = $request->status;
    $os->atualizado_por_user_id = auth()->id();

    // Se quiser marcar a data de finalização automaticamente:
    if (in_array($request->status, ['PRONTA', 'CANCELADA'])) {
        $os->finalizado_em = now();
        $os->arquivada = true;
    }

    $os->save();

    
    $cliente = Cliente::find($os->cliente_id);
    $ordemServico = OrdemServico::find($os->id);
    //Notificação
    $cliente->notify(new OsStatusNotification($cliente, $ordemServico));
    
    return redirect()->route('os.show', $os->id)->with('success', 'Status atualizado com sucesso!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
