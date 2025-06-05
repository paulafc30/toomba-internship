<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Toomba') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Tailwind CDN (opcional) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS y JS con Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/folders.js'])

    <meta name="base-route" content="{{ route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index') }}">

    <style>
        .sidebar-image {
            background-image: url('https://static.vecteezy.com/system/resources/previews/000/440/965/non_2x/vector-folder-icon.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 150px;
            margin-bottom: 1rem;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">

    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Bootstrap Bundle JS (con Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts') <!-- Scripts adicionales desde vistas -->
</body>

</html>