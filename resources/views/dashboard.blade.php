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
                    
                    {{ __("You're logged in!") }}
                    
                    @if (Auth::user()->empresaAfiliada === null)
                        
                        <div class="mt-4">
                            <p class="mb-3">Você precisa criar uma empresa para continuar.</p>
                            
                            <a href="{{ route('empresa.create') }}" class="text-blue-500 hover:text-blue-700 font-bold underline">
                                Ir para: Nova Empresa
                            </a>
                        </div>
                        
                    @else
                        <p class="mt-4">
                            Sua empresa atual é: <strong>{{ Auth::user()->empresaAfiliada->nome }}</strong>.
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
