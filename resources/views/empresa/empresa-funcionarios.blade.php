<x-app-layout>
    
    {{-- 1. DEFINE O CONTEÚDO DO CABEÇALHO (O que vai em $header) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciar Funcionários') }}
        </h2>
    </x-slot>

    {{-- 2. DEFINE O CONTEÚDO PRINCIPAL (O que vai em $slot) --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SEÇÃO DE ADIÇÃO MANUAL DE FUNCIONÁRIO (Seu formulário) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Adicionar Funcionário por E-mail</h3>
                </div>
            </div>

            {{-- SEÇÃO DA LISTA DE FUNCIONÁRIOS --}}
            {{-- ... O conteúdo da lista de funcionários que definimos... --}}

        </div>
    </div>
</x-app-layout>