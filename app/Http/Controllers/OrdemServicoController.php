<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\OrdemServico;
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
        'liente_nome.required' => 'O nome do cliente é obrigatório.',
        'modelo.required' => 'O modelo do equipamento é obrigatório.',
        'relato.required' => 'O problema relatado é obrigatório.',
        ]);

        return DB::transaction(function () use ($request, $empresaId, $usuarioId) {

        // Encontra a última OS desta empresa para definir o próximo número sequencial
        $ultimoOS = OrdemServico::where('empresa_id', $empresaId)
                                ->latest('id')
                                ->first();
                
        // Determina o próximo número, baseado no último número sequencial ou começando em 1
        $novoNumeroSeq = $ultimoOS ? (int)$ultimoOS->id + 1 : 1;

        // Formata o número (Ex: 1 -> 0001) e o concatena com o ano (0001/2025)
        $numeroFormatado = str_pad($novoNumeroSeq, 4, '0', STR_PAD_LEFT);
        $numeroOS = "{$numeroFormatado}/" . date('Y');

        $cliente = Cliente::firstOrNew([
            'cnpj_cpf' => $request->input('cliente_cnpj_cpf'),
            'empresa_id' => $empresaId,
        ]);
        
        // Se o cliente não existir, ou se quisermos atualizar dados
        $cliente->fill([
            'nome' => $request->input('cliente_nome'),
            'telefone' => $request->input('cliente_telefone'),
            'email' => $request->input('cliente_email'),
            'empresa_id' => $empresaId, // Garante que a empresa_id está setada
        ]);

        $cliente->save();

        //Cria equipamento
        $equipamento = Equipamento::create([
            'empresa_id' => $empresaId,
            'cliente_id' => $cliente->id, // Vínculo com o Cliente recém-salvo
            'equip_marca' => $request->input('equip_marca'),
            'equip_modelo' => $request->input('equip_modelo'),
            'equip_numero_serie' => $request->input('equip_numero_serie'),
        ]);

        $os = OrdemServico::create([
            'empresa_id' => $empresaId,
            'cliente_id' => $cliente->id,
            'equipamento_id' => $equipamento->id,
            
            // Usuários e Status
            'criador_user_id' => $usuarioId, // Quem está abrindo a OS
            // 'atualizado_por_user_id' => $usuarioId, // Pode ser preenchido aqui
            'status' => 'ABERTA', 
            
            // Dados da OS
            'numero' => $numeroOS, // O número sequencial gerado
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
    public function show(string $id)
    {
        //
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
