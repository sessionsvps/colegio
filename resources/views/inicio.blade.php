<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-p-0">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Sideral Carrión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/@material-tailwind/html@latest/styles/material-tailwind.css" />
    
    <link rel="preconnect" href="https://fonts.bunny.net">    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="https://universidadsideralcarrion.com/storage/img/icons/usc.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <header class="fixed w-full z-50 bg-slate-50 shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="text-gray-800">
                    <a href="#" class="flex items-center gap-x-4">
                        <img src="{{ asset('img/sideral-logo.jpg') }}" class="w-16 h-auto" alt="logo">
                        <div class="text-center">
                            <span class="block font-bold text-xl">Colegio Sideral Carrión</span>
                            <span class="block text-base text-gray-600">¿Eres o no eres?</span>
                        </div>
                    </a>
                </div>
                <nav>
                    <ul class="flex space-x-10">
                        <li><a href="#home" class="text-gray-600 hover:text-indigo-600 space-x-2"><i class="fa-solid fa-house"></i><span>Inicio</span></a></li>
                        {{-- <li><a href="#about" class="text-gray-600 hover:text-indigo-600 space-x-2"><i class="fa-solid fa-school"></i><span>Nosotros</span></a></li>
                        <li><a href="#services" class="text-gray-600 hover:text-indigo-600 space-x-2"><i class="fa-solid fa-address-book"></i><span>Contacto</span></a></li> --}}
                        @auth
                            <li><a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-indigo-600 space-x-2"><i class="fa-solid fa-user"></i><span>Bienvenido, {{ Auth::user()->full_name }}</span></a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 space-x-2"><i class="fa-solid fa-right-to-bracket"></i><span>Iniciar sesión</span></a></li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <section id="home" class="relative bg-cover bg-center h-screen" style="background-image: url('{{ asset('img/p1.png') }}');">
        <div class="absolute inset-0 bg-black opacity-40"></div> <!-- Capa con opacidad -->
        <div class="container mx-auto h-full flex items-center justify-center relative">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-black">Bienvenido a Sideral Carrión</h1>
                <p class="mt-4 text-lg md:text-2xl">Sumérgete en las <span class="font-semibold">Ciencias del Fracaso</span>.</p>
                <div class="mt-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded">Login</a>
                    @endauth
                    {{-- <a href="{{ route('register') }}" class="btn bg-gray-600 hover:bg-gray-800 text-white px-4 py-2 rounded">Register</a> --}}
                </div>
            </div>
        </div>
    </section>

    {{-- <section id="about" class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800">About Us</h2>
            <p class="mt-4 text-gray-600">¡ Imagina sumergirte en las Ciencias del Fracaso, donde aprenderás valiosas lecciones de los desafíos y adversidades, convirtiendo cada obstáculo en una oportunidad de crecimiento. Adentrarte en las Ciencias del Abandono, explorando el arte de dejar ir lo que ya no sirve para avanzar hacia nuevas posibilidades. Conoce nuestros nuevos campus !</p>
        </div>
    </section>

    <section id="services" class="py-16 bg-gray-50">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800">Our Services</h2>
            <p class="mt-4 text-gray-600">Details of the services provided by the application.</p>
        </div>
    </section>

    <section id="contact" class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800">Contact Us</h2>
            <p class="mt-4 text-gray-600">Contact information.</p>
        </div>
    </section> --}}

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Your Application. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/script-name.js"></script>  
</body>
</html>
