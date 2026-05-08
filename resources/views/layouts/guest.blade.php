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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg-image-container {
                background-image: url('https://renouvo.net/wp-content/uploads/2024/02/what-does-eco-friendly-mean-new-cover.jpg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                position: absolute;
                inset: 0;
                z-index: 0;
                animation: slowPan 20s ease-in-out infinite alternate;
            }

            @keyframes slowPan {
                0% { transform: scale(1) translateX(0); }
                100% { transform: scale(1.1) translateX(-20px); }
            }

            .bg-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.6) 100%);
                z-index: 1;
            }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .animate-fade-in-up {
                animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            .stagger-1 { animation-delay: 0.1s; }
            .stagger-2 { animation-delay: 0.2s; }
            .stagger-3 { animation-delay: 0.3s; }
            .stagger-4 { animation-delay: 0.4s; }

            /* Floating animation for icons */
            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(10deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .leaf-1 { top: 10%; left: 10%; animation-delay: 0s; }
            .leaf-2 { top: 70%; left: 80%; animation-delay: 2s; }
            .leaf-3 { top: 40%; left: 70%; animation-delay: 4s; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 overflow-hidden">
        <div class="min-h-screen flex">
            <!-- Left Side: Image Panel -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden group bg-gray-900">
                <div class="bg-image-container"></div>
                <div class="bg-overlay"></div>
                
                <!-- Floating Eco Icons -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
                    <i class="fa-solid fa-leaf text-green-400/20 text-6xl absolute animate-float leaf-1"></i>
                    <i class="fa-solid fa-leaf text-green-300/10 text-8xl absolute animate-float leaf-2"></i>
                    <i class="fa-solid fa-leaf text-green-200/15 text-4xl absolute animate-float leaf-3"></i>
                </div>

                <div class="relative z-20 flex flex-col justify-center px-16 text-white">
                    <h1 class="text-6xl font-bold leading-tight mb-6 animate-fade-in-up stagger-1">
                        Sustainable Living<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-500">Starts Here.</span>
                    </h1>
                    <p class="text-xl text-gray-200 max-w-md animate-fade-in-up stagger-2">Join our mission to create a cleaner, greener world through smart waste management and the circular economy.</p>
                    
                    <div class="mt-12 flex items-center space-x-4 animate-fade-in-up stagger-3">
                        <div class="flex -space-x-2">
                            <div class="w-12 h-12 rounded-full border-4 border-gray-800 bg-green-500 flex items-center justify-center text-xs font-bold ring-2 ring-green-400/30">1k+</div>
                            <div class="w-12 h-12 rounded-full border-4 border-gray-800 bg-blue-500 ring-2 ring-blue-400/30"></div>
                            <div class="w-12 h-12 rounded-full border-4 border-gray-800 bg-yellow-500 ring-2 ring-yellow-400/30"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-300 uppercase tracking-widest">Trusted by over 1,000 citizens</span>
                    </div>
                </div>

                <div class="absolute bottom-8 left-16 z-10 flex items-center space-x-2 text-gray-400 text-xs">
                    <span>Powered by EcoWaste System</span>
                    <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                    <span>2024 Version</span>
                </div>
            </div>

            <!-- Right Side: Form Panel -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 bg-white relative z-20">
                <div class="w-full max-w-md animate-fade-in-up stagger-4">
                    <div class="mb-10 text-center lg:text-left group">
                        <a href="/" class="inline-block mb-6 transition-transform duration-300 group-hover:scale-110">
                            <x-application-logo class="w-16 h-16 fill-current text-green-600 drop-shadow-md" />
                        </a>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
