<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Ordem de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="space-y-8">

                    <!-- Informações do Cliente -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">1. Informações do Cliente</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="nome" :value="__('Nome/Razão Social')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" value="{{ $os->cliente?->nome ?? 'Cliente não encontrado' }}" disabled />
                            </div>

                            <div>
                                <x-input-label for="cnpj_cpf" :value="__('CNPJ/CPF')" />
                                <x-text-input id="cnpj_cpf" class="block mt-1 w-full" type="text" value="{{ $os->cliente?->cnpj_cpf ?? 'Não informado' }}" disabled />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="telefone" :value="__('Telefone')" />
                                <x-text-input id="telefone" class="block mt-1 w-full" type="text" value="{{ $os->cliente?->telefone ?? 'Não informado' }}" disabled />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('E-mail')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" value="{{ $os->cliente?->email ?? 'Não informado' }}" disabled />
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Equipamento -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4 border-b pb-2">2. Dados do Equipamento</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="equip_modelo" :value="__('Modelo')" />
                                <x-text-input id="equip_modelo" class="block mt-1 w-full" type="text" value="{{ $os->equipamento?->equip_modelo ?? 'Não informado' }}" disabled />
                            </div>

                            <div>
                                <x-input-label for="equip_marca" :value="__('Marca')" />
                                <x-text-input id="equip_marca" class="block mt-1 w-full" type="text" value="{{ $os->equipamento?->equip_marca ?? 'Não informado' }}" disabled />
                            </div>

                            <div>
                                <x-input-label for="equip_numero_serie" :value="__('Nº de Série')" />
                                <x-text-input id="equip_numero_serie" class="block mt-1 w-full" type="text" value="{{ $os->equipamento?->equip_numero_serie ?? 'N/S' }}" disabled />
                            </div>
                        </div>
                    </div>

                    <!-- Detalhes da OS -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4 border-b pb-2">3. Detalhes da OS</h3>

                        <div class="mt-4">
                            <x-input-label for="problema_relato" :value="__('Problema Relatado')" />
                            <textarea id="problema_relato" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" disabled>{{ $os->problema_relato ?? 'Não informado' }}</textarea>
                        </div>

                        <div class="mt-4 md:w-1/4">
                            <x-input-label for="prazo_previsto" :value="__('Prazo Previsto (Data)')" />
                            <x-text-input id="prazo_previsto" class="block mt-1 w-full" type="date" 
                                value="{{ $os->prazo_previsto ? \Carbon\Carbon::parse($os->prazo_previsto)->format('Y-m-d') : 'Não definido' }}" 
                                disabled />
                        </div>
                    </div>

                    <!-- Responsabilidade e Atualizações -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4 border-b pb-2">4. Responsabilidade e Atualizações</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="criador" :value="__('Criado por')" />
                                <x-text-input id="criador" class="block mt-1 w-full" type="text" value="{{ $os->criador?->name ?? 'Usuário Desconhecido' }}" disabled />
                            </div>

                            <div>
                                <x-input-label for="atualizador" :value="__('Atualizado por')" />
                                <x-text-input id="atualizador" class="block mt-1 w-full" type="text" value="{{ $os->atualizador?->name ?? 'Não foi atualizado' }}" disabled />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="created_at" :value="__('Data de Abertura')" />
                                <x-text-input id="created_at" class="block mt-1 w-full" type="text" 
                                    value="{{ $os->created_at ? \Carbon\Carbon::parse($os->created_at)->format('d/m/Y H:i') : 'N/A' }}" 
                                    disabled />
                            </div>

                            <div>
                                <x-input-label for="finalizado_em" :value="__('Finalizado em')" />
                                <x-text-input id="finalizado_em" class="block mt-1 w-full" type="text" 
                                    value="{{ $os->finalizado_em ? \Carbon\Carbon::parse($os->finalizado_em)->format('d/m/Y H:i') : 'Pendente' }}" 
                                    disabled />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
