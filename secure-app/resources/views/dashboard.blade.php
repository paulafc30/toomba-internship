<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Welcome, {{ auth()->user()->name }}!</h2>
                <div>
                    {{-- Space for elements on the right side of the header if you have any --}}
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-4 gap-4">

                    <aside class="col-span-1 bg-gray-800 text-white rounded-lg shadow-md p-4">
                        <nav>
                            <ul class="space-y-4">
                                @if (auth()->user()->user_type === 'administrator')
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="{{ route('admin.users') }}" class="block px-4 py-2 rounded hover:bg-gray-600 {{ request()->routeIs('admin.users') ? 'bg-gray-600' : '' }}">
                                            Manage Users
                                        </a>
                                    </li>
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="{{ route('admin.folders.index') }}" class="block px-4 py-2 rounded hover:bg-gray-600 {{ request()->routeIs('admin.folders.*') ? 'bg-gray-600' : '' }}">
                                            Manage Folders
                                        </a>
                                    </li>
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="{{ route('admin.temporary-link.index') }}" class="block px-4 py-2 rounded hover:bg-gray-600 {{ request()->routeIs('admin.temporary-link.*') ? 'bg-gray-600' : '' }}">
                                            Send Temporary Link
                                        </a>
                                    </li>
                                @elseif (auth()->user()->user_type === 'client' || auth()->user()->user_type === 'temporary')
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="{{ route('client.folders.index') }}" class="block px-4 py-2 rounded hover:bg-gray-600 {{ request()->routeIs('client.folders.*') ? 'bg-gray-600' : '' }}">
                                            View My Folders
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </aside>

                    <main class="col-span-3 bg-white p-6 rounded-lg shadow-md">
                        @if (auth()->user()->user_type === 'administrator')
                            @if (request()->routeIs('admin.folders.*'))
                                @include('admin.folders.index') {{-- Assumes you have an index.blade.php file in admin/folders --}}
                            @else
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <h1 class="text-xl font-semibold mb-4">Administrator Panel</h1>
                                    <h2>Administrator Features:</h2>
                                    <p>Select an option from the left menu.</p>
                                </div>
                            @endif
                        @elseif (auth()->user()->user_type === 'client' || auth()->user()->user_type === 'temporary')
                            @if (request()->routeIs('client.folders.*'))
                                @include('client.folders.index') {{-- Assumes you have an index.blade.php file in client/folders --}}
                            @else
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <h1 class="text-xl font-semibold mb-4">Client Area</h1>
                                    <p class="mb-2">Welcome, {{ auth()->user()->name }}!</p>
                                    <p>You can view your folders using the menu on the left.</p>
                                </div>
                            @endif
                        @endif
                    </main>
                </div>
            </div>
        </div>
    </x-app-layout>

    @push('styles')
    <style>
        /* Additional Custom Styles */
        .font-semibold {
            font-weight: bold;
        }

        .hover\:bg-gray-600:hover {
            background-color: #4B5563;
        }

        /* Active Link Style */
        .bg-gray-600 {
            background-color: #4B5563;
        }

        /* Sidebar Items Transition */
        nav a {
            transition: all 0.2s ease-in-out;
        }
    </style>
    @endpush

</body>

</html>