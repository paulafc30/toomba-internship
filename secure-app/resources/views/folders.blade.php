@vite('resources/js/folders.js')
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

                            {{-- FORMULARIO DE BÚSQUEDA Y FILTROS --}}
                            <form method="GET" action="{{ route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index')}}" class="space-y-4">

                                {{-- BARRA DE BÚSQUEDA --}}
                                <div class="flex w-full gap-2">
                                    <input
                                        type="text"
                                        name="search"
                                        id="search-input"
                                        value="{{ request('search') }}"
                                        placeholder="Buscar carpeta..."
                                        class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />

                                    <button type="submit" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 transition">
                                        Search
                                    </button>

                                    <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition">
                                        <i class="bi bi-x-circle"></i>
                                    </button>

                                    <button type="button" id="toggle-filters" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 flex items-center gap-1 transition">
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

                                        <a href="{{  route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index') }}"
                                            class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition flex items-center gap-1">
                                            <i class="bi bi-x-circle"></i> Clean filters
                                        </a>
                                    </div>
                                </div>
                            </form>


                            @if ($folders->isEmpty())
                            <p class="text-gray-600">
                                {{ request('search') ? __('The folder cannot be found.') : (Auth::check() && Auth::user()->user_type === 'administrator' ? __('There are no folders registered yet.') : __('You do not have access to any folders yet.')) }}
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