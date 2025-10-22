<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
{{ __('Dashboard') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                @if (Auth::user()->empresaAfiliada === null)
                    
                    <div class="mt-4">
                        <p class="mb-3">Você não está afiliado a nehuma empresa! Clique no botão a seguir para criar uma nova empresa.</p>

                        <a
                            href="{{ route('empresa.create') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Nova Empresa
                        </a>
                    </div>
                    
                @else
                    <!-- INFORMAÇÕES DA EMPRESA E BOTÃO DE NOVA OS -->
                    <div class="mb-6 flex items-center justify-between">
                        <p class="mt-0 text-gray-600 dark:text-gray-400">
                            Empresa Atual: <strong>{{ Auth::user()->empresaAfiliada->nome }}</strong>
                        </p>
                    </div>

                    <!-- LISTAGEM DE ORDENS DE SERVIÇO ATIVAS -->
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200 border-t pt-4">Ordens de Serviço Arquivadas</h3>

                    @if ($ordensServico->isEmpty())
                        <div class="p-4 text-center border rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            Não há Ordens de Serviço abertas ou em andamento no momento.
                        </div>
                    @else
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            OS / Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Cliente
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Equipamento / Defeito
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach ($ordensServico as $os)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location='{{ route('os.show', $os->id) }}'">
                                        
                                        <!-- Nº OS / Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">#{{ $os->id }}</div>
                                            @php
                                                $color = [
                                                    'ABERTA' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                                    'EM_ESPERA' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                                    'AGUARDANDO_PEÇAS' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                                    'EM_DIAGNOSTICO' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                                    'EM_REPARO' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                                    'PRONTA' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                                    'CANCELADA' => 'bg-gray-100 text-gray-800 dark:bg-gray-500 dark:text-gray-100',
                                                ][$os->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                            @endphp
                                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ str_replace('_', ' ', $os->status) }}
                                            </span>
                                        </td>

                                        <!-- Cliente -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $os->cliente->nome ?? 'Cliente Não Encontrado' }}
                                        </td>

                                        <!-- Equipamento / Defeito -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $os->equipamento?->modelo ?? 'Modelo N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                Defeito: {{ $os->problema_relato }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Links de Paginação -->
                        <div class="mt-4">
                            {{ $ordensServico->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>


</x-app-layout>