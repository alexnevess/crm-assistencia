<x-guest-layout>
    <form method="POST" action="{{ route('registra.funcionario') }}">
        @csrf

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email do funcionário')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    
    <!-- Perfil de Acesso -->
    <div>
        <x-input-label for="perfil_acesso" :value="__('Perfil de Acesso')" />
        <select id="perfil_acesso" name="perfil_acesso" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="ATENDENTE" {{ old('perfil_acesso') == 'ATENDENTE' ? 'selected' : '' }}>Atendente</option>
            <option value="TECNICO" {{ old('perfil_acesso') == 'TECNICO' ? 'selected' : '' }}>Técnico</option>
            <option value="ADMIN" {{ old('perfil_acesso') == 'ADMIN' ? 'selected' : '' }}>Admin (Empresa)</option>
        </select>
        <x-input-error :messages="$errors->get('perfil_acesso')" class="mt-2" />
    </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>