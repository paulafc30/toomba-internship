<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Permissions') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md sm:rounded-lg p-6">

                    <h3 class="text-lg font-semibold mb-6">
                        {{ __('Editing Permissions for: ') }} <span class="font-normal">{{ $user->name }}</span>
                    </h3>

                    <h3 class="text-lg font-semibold mb-4">{{ __('Folder Permissions') }}</h3>

                    @if ($folders->isEmpty())
                    <p class="text-gray-600">{{ __('No folders available yet.') }}</p>
                    @else
                    <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            @foreach ($folders as $folder)
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b pb-4">
                                <label for="folder_{{ $folder->id }}" class="flex items-center text-gray-800 font-medium mb-2 md:mb-0">
                                    <img src="{{ asset('images/folder-icon.png') }}" alt="Folder Icon" class="w-5 h-5 mr-2">
                                    {{ $folder->name }}
                                </label>


                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input
                                            type="radio"
                                            id="no_access_{{ $folder->id }}"
                                            name="permissions[{{ $folder->id }}]"
                                            value="no-access"
                                            class="text-gray-500 focus:ring-gray-400"
                                            {{ !isset($userPermissions[$folder->id]) || $userPermissions[$folder->id] === 'no-access' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">No access</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input
                                            type="radio"
                                            id="view_{{ $folder->id }}"
                                            name="permissions[{{ $folder->id }}]"
                                            value="view"
                                            class="text-blue-600 focus:ring-blue-500"
                                            {{ isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'view' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">View</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input
                                            type="radio"
                                            id="edit_{{ $folder->id }}"
                                            name="permissions[{{ $folder->id }}]"
                                            value="edit"
                                            class="text-green-600 focus:ring-green-500"
                                            {{ isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'edit' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Edit</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-[#0464FA] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Back to Dashboard') }}
                            </a>
                            <x-primary-button class="w-full sm:w-auto">
                                {{ __('Save Permissions') }}
                            </x-primary-button>

                        </div>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </x-app-layout>

</body>

</html>