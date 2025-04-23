<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
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
                <div class="grid grid-cols-5 gap-4">
                    {{-- Left Panel (Sidebar) --}}
                    <aside class="col-span-1 bg-white shadow-sm sm:rounded-lg p-6">
                        <nav>
                            <ul>
                                @if (auth()->user()->user_type === 'administrator')
                                <li class="mb-2"><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'font-semibold' : '' }}">Manage Users</a></li>
                                <li class="mb-2"><a href="{{ route('admin.folders.index') }}" class="{{ request()->routeIs('admin.folders.*') ? 'font-semibold' : '' }}">Manage Folders</a></li>
                                @elseif (auth()->user()->user_type === 'client')
                                <li class="mb-2"><a href="{{ route('client.folders.index') }}" class="{{ request()->routeIs('client.folders.*') ? 'font-semibold' : '' }}">View My Folders</a></li>
                                @endif
                            </ul>
                        </nav>
                    </aside>

                    {{-- Right Panel (Main Content) --}}
                    <main class="col-span-4">
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
                        @elseif (auth()->user()->user_type === 'client')
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
        .font-semibold {
            /* To highlight the active link */
            font-weight: bold;
        }
    </style>
    @endpush

    @push('scripts')
    {{-- You don't need to modify the scripts for this structure --}}
    </script>
    @endpush
</body>

</html>