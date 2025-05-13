<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<body>
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
                        <div class="flex space-x-6">
                            <div class="w-1/4 p-4 rounded-lg shadow-md">
                                <div class="sidebar-image">

                                </div>

                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                <a href="{{ route('admin.folders.create') }}" class="bg-green-500 hover:bg-green-700 font-bold py-2 px-4 rounded text-white w-full text-center">{{ __('Create New Folder') }}</a>
                                @endif
                                
                            </div>

                            <!-- Folder List Section -->
                            <div class="flex-1">
                                @if (session('success'))
                                <div class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
                                    {{ session('success') }}
                                </div>
                                @endif

                                @if ($folders->isEmpty())
                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                <p>{{ __('There are no folders registered yet.') }}</p>
                                @else
                                <p>{{ __('You do not have access to any folders yet.') }}</p>
                                @endif
                                @else
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Updated At') }}</th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">{{ __('Actions') }}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($folders as $folder)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ Auth::user()->user_type === 'administrator' ? route('admin.folders.files', $folder->id) : route('client.folders.files', $folder->id) }}" class="text-blue-500 hover:underline">{{ $folder->name }}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $folder->updated_at }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                                <a href="{{ route('admin.folders.edit', $folder->id) }}" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded border border-black">{{ __('Rename') }}</a>
                                                <form action="{{ route('admin.folders.destroy', $folder->id) }}" method="POST" class="inline-block ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <<button type="submit" class="bg-red-500 font-bold py-2 px-4 rounded" onclick="return confirm('{{ __('Are you sure you want to delete this folder?') }}')">{{ __('Delete Folder') }}</button>
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
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">{{ __('Back to Dashboard') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>