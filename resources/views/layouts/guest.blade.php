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
                            <h2 class="text-4xl font-bold text-center text-gray-700 dark:text-white">Brand</h2>
    
                            <p class="mt-3 text-gray-500 dark:text-gray-300">Sign in to access your account</p>
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
                            <h2 class="text-4xl font-bold text-white">Brand</h2>
    
                            <p class="max-w-xl mt-3 text-gray-300">Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                In autem ipsa, nulla laboriosam dolores, repellendus perferendis libero suscipit nam
                                temporibus molestiae</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
