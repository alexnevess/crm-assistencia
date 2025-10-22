<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\OrdemServico;

use App\Models\User;
use Illuminate\Support\Facades\DB;



class OrdemServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    // Carregar as relações com eager loading
    $os = OrdemServico::with('cliente', 'equipamento', 'criador', 'atualizador')->find($id);

    if (!$os) {
        abort(404, 'Ordem de Serviço não encontrada');
    }

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
