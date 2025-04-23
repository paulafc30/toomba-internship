<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Toomba</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex items-center justify-center">
                <button class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold py-2 px-4 rounded-md shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                    <a href="{{ route('login') }}">
                        Log in
                    </a>
                </button>

                @if (Route::has('register'))
                    <button class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold py-2 px-4 rounded-md shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <a href="{{ route('register') }}">
                            Register
                        </a>
                    </button>
                @endif
            </div>
        </div>
    </div>
</body>
</html>