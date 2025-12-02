<x-layout>
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="/applications_view" 
               class="text-sm text-primary-600 hover:underline mb-4 inline-block">
                &larr; Ir a Aplicaciones
            </a>

            {{-- HEADER --}}
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-6">
                <div class="flex items-start gap-4">

                    {{-- Avatar --}}
                    <div class="w-16 h-16 rounded-md bg-primary-50 
                                flex items-center justify-center 
                                text-primary-700 font-bold text-2xl">
                        {{ strtoupper(substr($application->name, 0, 1)) }}
                    </div>

                    {{-- Main info --}}
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $application->name }}
                        </h1>

                        {{-- System badge --}}
                        @if($application->system)
                            <div class="mt-2">
                                <span class="inline-block bg-gray-100 text-gray-700 
                                            text-xs px-2 py-1 rounded">
                                    {{ $application->system->name }}
                                </span>
                            </div>
                        @endif

                        {{-- Description --}}
                        <p class="mt-4 text-gray-700">
                            {{ $application->description ?? 'Sin descripción disponible.' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- SHORTCUTS SECTION --}}
            @if($shortcutsByCategory->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    No hay atajos registrados para esta aplicación.
                </div>
            @else

                @foreach($shortcutsByCategory as $categoryName => $shortcuts)
                    <section class="mb-8">

                        <h2 class="text-lg font-semibold text-gray-800 mb-3">
                            {{ $categoryName }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                            @foreach($shortcuts as $sc)
                                <a href="/shortcuts/{{ $sc->id }}"
                                   class="block bg-white border border-gray-200 
                                          rounded-lg p-3 hover:shadow transition-shadow duration-150">
                                    
                                    <div class="flex items-start gap-3">

                                        {{-- keys --}}
                                        <div class="flex-none bg-gray-100 text-gray-800 
                                                    px-3 py-1 rounded font-mono text-sm">
                                            {{ $sc->keys }}
                                        </div>

                                        {{-- description --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-800 truncate">
                                                {{ $sc->description ?? $sc->keys }}
                                            </div>
                                        </div>

                                    </div>
                                </a>
                            @endforeach

                        </div>

                    </section>
                @endforeach

            @endif

        </div>
    </section>
</x-layout>
