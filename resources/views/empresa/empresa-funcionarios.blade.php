<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciar Funcionários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Seção de adição de funcionário -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Adicionar Funcionário por E-mail</h3>
                    <a
            href="{{ route('adiciona.funcionario') }}"
            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
            Adicionar Funcionário +
        </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-4">
                        Funcionários Atuais ({{ $funcionarios->count() }})
                    </h3>
                    
                    @if (!$empresa)
                        <p class="text-red-500 dark:text-red-400">
                            Você precisa ter uma empresa registrada para visualizar funcionários.
                        </p>
                    @elseif ($funcionarios->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">
                            Nenhum funcionário vinculado à empresa {{ $empresa->nome }} ainda.
                        </p>
                    @else
                        {{-- Tabela de Listagem --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perfil</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($funcionarios as $funcionario)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $funcionario->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $funcionario->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                             <form action="{{ route('funcionario.update.perfil', $funcionario->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT') <!--  Simula a requisição PUT para atualização  -->

                                                @php
                                                    $perfilAtual = $funcionario->perfil_acesso;
                                                @endphp

                                                <select id="perfil_acesso" name="perfil_acesso" 
                                                        class="border-gray-300 ... rounded-md shadow-sm text-sm" 
                                                        onchange="this.form.submit()">  {{-- <-- AQUI! Quando o valor mudar, o formulário é enviado. --}}
                                                    
                                                    <option value="ATENDENTE" {{ $perfilAtual == 'ATENDENTE' ? 'selected' : '' }}>Atendente</option>
                                                    <option value="TECNICO" {{ $perfilAtual == 'TECNICO' ? 'selected' : '' }}>Técnico</option>
                                                    <option value="ADMIN" {{ $perfilAtual == 'ADMIN' ? 'selected' : '' }}>Admin (Empresa)</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $funcionario->telefone ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                   

                                            <!-- Formulário para exclusão de funcionário -->
                                            <form action="{{ route('funcionario.remover', $funcionario->id) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('Tem certeza que deseja desvincular {{ $funcionario->name }} da sua empresa? Ele perderá o acesso a todos os recursos internos.');">
                                                @csrf
                                                @method('DELETE') {{-- Necessário para simular a requisição DELETE --}}
                                                
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                                    Remover
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>