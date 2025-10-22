<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Abrir Nova Ordem de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <form method="POST" action="{{ route('os.store') }}">
                    @csrf

                    <h3 class="text-lg font-medium mb-4 border-b pb-2">1. Informações do Cliente</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nome" :value="__('Nome/Razão Social*')" />
                            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome')" required autofocus />
                            <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="cnpj_cpf" :value="__('CNPJ/CPF')" />
                            <x-text-input id="cnpj_cpf" class="block mt-1 w-full" type="text" name="cnpj_cpf" :value="old('cnpj_cpf')" />
                            <x-input-error :messages="$errors->get('cnpj_cpf')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" class="block mt-1 w-full" type="text" name="telefone" :value="old('telefone')" />
                            <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('E-mail')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-medium mt-8 mb-4 border-b pb-2">2. Dados do Equipamento</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="equip_modelo" :value="__('Modelo*')" />
                            <x-text-input id="equip_modelo" class="block mt-1 w-full" type="text" name="equip_modelo" :value="old('equip_modelo')" required />
                            <x-input-error :messages="$errors->get('equip_modelo')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="equip_marca" :value="__('Marca')" />
                            <x-text-input id="equip_marca" class="block mt-1 w-full" type="text" name="equip_marca" :value="old('equip_marca')" />
                            <x-input-error :messages="$errors->get('equip_marca')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="equip_numero_serie" :value="__('Nº de Série')" />
                            <x-text-input id="equip_numero_serie" class="block mt-1 w-full" type="text" name="equip_numero_serie" :value="old('equip_numero_serie')" />
                            <x-input-error :messages="$errors->get('equip_numero_serie')" class="mt-2" />
                        </div>
                    </div>

                    <h3 class="text-lg font-medium mt-8 mb-4 border-b pb-2">3. Detalhes da OS</h3>

                    <div class="mt-4">
                        <x-input-label for="problema_relato" :value="__('Problema Relatado*')" />
                        <textarea id="problema_relato" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="problema_relato" rows="4" required>{{ old('problema_relato') }}</textarea>
                        <x-input-error :messages="$errors->get('problema_relato')" class="mt-2" />
                    </div>

                    <div class="mt-4 md:w-1/4">
                        <x-input-label for="prazo_previsto" :value="__('Prazo Previsto (Data)')" />
                        <x-text-input id="prazo_previsto" class="block mt-1 w-full" type="date" name="prazo_previsto" :value="old('prazo_previsto')" />
                        <x-input-error :messages="$errors->get('prazo_previsto')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="ms-4">
                            {{ __('Abrir Ordem de Serviço') }}
                        </x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>