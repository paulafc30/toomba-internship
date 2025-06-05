<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Toomba Secure</title>

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/js/folders.js')

    {{-- Bootstrap Icons CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-gray-100">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Files') }}
                @isset($folder)
                {{ __('in Folder:') }} {{ $folder->name }}
                @endisset
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Panel Izquierdo: Upload --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="w-full h-28 bg-contain bg-center bg-no-repeat mb-6"
                        style="background-image: url('https://cdn-icons-png.freepik.com/512/6700/6700085.png');"></div>

                    @isset($folder)
                    @php
                    $userPermission = null;
                    if (Auth::check()) {
                    $userPermission = Auth::user()->user_type === 'administrator'
                    ? 'edit'
                    : \App\Models\Permission::where('user_id', Auth::id())
                    ->where('folder_id', $folder->id)
                    ->value('permission_type');
                    }
                    @endphp

                    @if (Auth::check() && $userPermission === 'edit')
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-4">{{ __('Upload files') }}</h2>

                        @if (session('success'))
                        <div id="success-message" class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
                        @endif

                        <div class="flex flex-col space-y-3">
                            <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center text-gray-600 hover:bg-gray-100 cursor-pointer">
                                {{ __('Drag and drop a file here or click to select') }}
                            </div>

                            <input type="file" name="file" id="fileInput" class="hidden" />
                            <input type="text" id="fileName" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm" placeholder="{{ __('No file selected') }}" />
                            <button type="button" id="uploadButton" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700" disabled>
                                {{ __('Upload File') }}
                            </button>
                        </div>
                    </div>
                    @endif
                    @endisset
                </div>

                {{-- Panel derecho: File List --}}
                <div class="bg-white shadow-sm sm:rounded-lg col-span-1 lg:col-span-3 p-6 text-gray-900">
                    <h1 class="text-lg font-semibold mb-4">{{ __('Files List') }}</h1>

                    {{-- FORMULARIO DE BÚSQUEDA Y FILTROS --}}
                    <form method="GET" action="{{ isset($folder) ? route('admin.folders.files', $folder->id) : route('files.view') }}" class="space-y-4">

                        {{-- BARRA DE BÚSQUEDA --}}
                        <div class="flex w-full gap-2">
                            <input
                                type="text"
                                name="search"
                                id="search-input"
                                value="{{ request('search') }}"
                                placeholder="Search files..."
                                class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />

                            <button type="submit" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 transition">
                                Search
                            </button>

                            <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition">
                                <i class="bi bi-x-circle"></i>
                            </button>

                            <button type="button" id="filter-button" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 flex items-center gap-1 transition">
                                <span>Filters</span>
                                <i id="filter-icon" class="bi bi-funnel"></i>
                            </button>

                        </div>

                        {{-- FILTROS AVANZADOS (mostrados al hacer toggle) --}}
                        <div id="advancedFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 hidden">

                            <div class="flex flex-col gap-1">
                                <label for="date_from" class="text-sm">From:</label>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                    class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="date_to" class="text-sm">To:</label>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                    class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />
                            </div>

                            {{-- Botones de filtros --}}
                            <div class="flex items-end gap-2 mt-1 md:col-span-2">
                                <button type="submit" class="bg-green-600 text-white px-4 py-1 text-sm rounded-full hover:bg-green-700 transition">
                                    Apply filters
                                </button>

                                <a href="{{ isset($folder) ? route('admin.folders.files', $folder->id) : route('files.view') }}"
                                    class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition flex items-center gap-1">
                                    <i class="bi bi-x-circle"></i> Clean filters
                                </a>
                            </div>
                        </div>
                    </form>



                    {{-- Lista de archivos --}}
                    <ul id="fileList" class="divide-y divide-gray-200 mb-4">
                        @if (isset($files) && $files->isNotEmpty())
                        @foreach ($files as $file)
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $file->name }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('files.view', $file->id) }}" target="_blank" class="bg-[#0464FA] text-white px-2 py-1 rounded hover:bg-blue-600 text-xs">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if (isset($folder))
                                @if ($userPermission === 'edit')
                                <a href="{{ route('files.download', $file->id) }}" class="text-white px-2 py-1 rounded text-xs" style="background-color: #048D6B;">
                                    <i class="bi bi-download"></i>
                                </a>

                                <form action="{{ route('admin.folders.files.destroy', ['folder' => $file->folder_id, 'file' => $file->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @elseif ($userPermission === 'view')
                                <a href="{{ route('files.download', $file->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                @else
                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                <a href="{{ route('files.download', $file->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('files.standalone.destroy', $file->name) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('files.download', $file->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                @endif
                            </div>
                        </li>
                        @endforeach
                        @else
                        <li class="text-gray-500 py-2">{{ __('No files available.') }}</li>
                        @endif
                    </ul>

                    {{-- Botón volver --}}
                    <a href="{{ route('admin.folders.index') }}" class="inline-block bg-[#1F2937] text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                        {{ __('Back to Folder List') }}
                    </a>

                </div>
            </div>
        </div>
    </x-app-layout>
</body>
</html>