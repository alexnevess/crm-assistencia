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
                        <p class="mt-0">
                            <strong>{{ Auth::user()->empresaAfiliada->nome }}</strong>
                        </p>
                        <a
                            href="{{ route('os.create') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Registrar Ordem de Serviço +
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
