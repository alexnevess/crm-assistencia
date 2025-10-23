<section>
    <header>
        @if ($empresa && $user->perfil_acesso === 'ADMIN')
            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-300 mb-4">
                Dados da Empresa
            </h3>

            <form method="POST" action="{{ route('empresa.update', $empresa->id) }}">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="empresa_nome" :value="__('Nome da Empresa')" />
                    <x-text-input id="empresa_nome" name="empresa_nome" type="text"
                        class="mt-1 block w-full" :value="old('empresa_nome', $empresa->nome)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('empresa_nome')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="cnpj" :value="__('CNPJ')" />
                    <x-text-input id="cnpj" name="cnpj" type="text"
                        class="mt-1 block w-full" :value="old('cnpj', $empresa->cpf_cnpj)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Salvar') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Salvo com sucesso.') }}
                        </p>
                    @endif
                </div>
            </form>
        @endif
    </header>
</section>
