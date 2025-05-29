<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>

<body class="bg-gray-50">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Folder List') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col lg:flex-row lg:space-x-6 space-y-6 lg:space-y-0">
                            <div class="w-full lg:w-1/4 p-4 rounded-lg shadow-md bg-white">
                                <div class="sidebar-image rounded-lg"></div>

                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                <a href="{{ route('admin.folders.create') }}"
                                    class="inline-flex items-center justify-center px-4 py-2  bg-[#0464FA] hover:bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest w-full transition duration-150 ease-in-out">
                                    {{ __('Create New Folder') }}
                                </a>
                                @endif
                            </div>

                            <div class="flex-1">
                                @if (session('success'))
                                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-4">
                                    {{ session('success') }}
                                </div>
                                @endif

                                {{-- Barra de búsqueda siempre visible --}}
                                <form method="GET" action="{{ route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index') }}" class="mb-4">
                                    <div class="flex w-full">
                                        <input
                                            type="text"
                                            name="search"
                                            id="search-input"
                                            value="{{ request('search') }}"
                                            placeholder="Buscar carpeta..."
                                            class="form-control w-full rounded-l border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">
                                            Buscar
                                        </button>
                                        <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-r text-sm hover:bg-gray-400">
                                            <i class="bi bi-x-circle"></i> Clear
                                        </button>
                                    </div>
                                </form>

                                @if ($folders->isEmpty())
                                <p class="text-gray-600">
                                    {{ request('search') ? __('No se encuentra la carpeta.') : (Auth::check() && Auth::user()->user_type === 'administrator' ? __('There are no folders registered yet.') : __('You do not have access to any folders yet.')) }}
                                </p>
                                @else
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <!--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Updated At') }}
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Actions') }}
                                            </th>-->
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($folders as $folder)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                                <a href="{{ Auth::user()->user_type === 'administrator' ? route('admin.folders.files', $folder->id) : route('client.folders.files', $folder->id) }}"
                                                    class="hover:underline">
                                                    {{ $folder->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $folder->updated_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if (Auth::user()->user_type === 'administrator')
                                                <a href="{{ route('admin.folders.edit', $folder->id) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-[#0464FA] hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-150 ease-in-out">
                                                    {{ __('Rename') }}
                                                </a>

                                                <form action="{{ route('admin.folders.destroy', $folder->id) }}"
                                                    method="POST" class="inline-block ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-150 ease-in-out"
                                                        onclick="return confirm('Are you sure you want to delete this folder?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    {{ $folders->links() }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-[#1F2937] hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                {{ __('Back to Dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        const searchInput = document.getElementById('search-input');
        const baseRoute = "{{ route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index') }}";

        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                window.location.href = baseRoute;
            }
        });

        document.getElementById('clear-search').addEventListener('click', function() {
            searchInput.value = '';
            window.location.href = baseRoute;
        });

        setTimeout(function() {
            var message = document.getElementById('success-message');
            if (message) {
                message.style.display = 'none';
            }
        }, 3000); // 3000 milisegundos = 3 segundos

    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>