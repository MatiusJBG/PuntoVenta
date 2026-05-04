<!DOCTYPE html>
<html lang="es" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PuntoVenta — @yield('title', 'Sistema')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 font-sans">

    <!-- Navbar -->
    <nav class="bg-slate-900 border-b border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <div class="flex flex-shrink-0 items-center">
                        <span class="text-white font-bold text-xl tracking-tight">PuntoVenta</span>
                    </div>
                    <div class="hidden sm:-my-px sm:ml-8 sm:flex sm:space-x-8">
                        <a href="{{ route('sales.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('sales.*') ? 'border-emerald-500 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-300' }}">
                            Ventas
                        </a>
                        <a href="{{ route('customers.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('customers.*') ? 'border-emerald-500 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-300' }}">
                            Clientes
                        </a>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('products.*') ? 'border-emerald-500 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-300' }}">
                            Productos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header / Breadcrumbs -->
    <header class="bg-white shadow-sm border-b border-zinc-200">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold leading-tight text-slate-900">
                @yield('header_title', 'Dashboard')
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Toast Notifications (SweetAlert2) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const Toast = window.Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', window.Swal.stopTimer)
                    toast.addEventListener('mouseleave', window.Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{!! addslashes(session('success')) !!}"
                });
            @endif

            @if($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Por favor, corrija los errores.',
                    text: "{!! addslashes($errors->first()) !!}"
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
