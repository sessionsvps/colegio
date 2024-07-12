<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body>
        <div class="bg-white dark:bg-gray-900">
            <div class="flex justify-center h-screen">
                <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
                    <div class="flex-1">
                        <div class="text-center">
                            <div class="mx-auto space-y-2">
                                <div><img src="{{ asset('img/sideral-logo.jpg') }}" alt="Logo de sideral" class="mx-auto w-40 h-auto"></div>
                                <h2 class="text-4xl font-bold text-center text-gray-700 dark:text-white">Sideral Carrión</h2>
                            </div>
    
                            <p class="mt-3 text-gray-500 dark:text-gray-300">Inicia sesión para acceder a tu cuenta</p>
                        </div>
    
                        <div class="mt-8">
                            {{$slot}}
                        </div>
                    </div>
                </div>
                <div class="hidden bg-cover lg:block lg:w-2/3"
                    style="background-image: url('{{ asset('img/p1.png') }}');">
                    <div class="flex items-center h-full px-20 bg-gray-900 bg-opacity-40">
                        <div>
                            <h2 class="text-4xl font-bold text-white">Universidad Sideral Carrión</h2>
                            <h3 class="text-xl font-bold text-white">Innovación, Conocimiento y Futuro</h3>
    
                            <p class="max-w-xl mt-3 text-gray-100">Bienvenidos a la Universidad Sideral Carrión. Nos dedicamos a la excelencia académica, la investigación avanzada y la formación de líderes comprometidos con el progreso sostenible. Aquí, tu futuro comienza hoy, wawita.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
