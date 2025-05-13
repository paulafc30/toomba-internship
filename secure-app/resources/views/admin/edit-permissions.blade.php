<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Permissions') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <h3 class="text-lg font-semibold mb-4">{{ __('Editing Permissions for: ') }}{{ $user->name }}</h3>

                        <h3 class="text-lg font-semibold mb-4">{{ __('Folder Permissions') }}</h3>

                        @if ($folders->isEmpty())
                            <p>{{ __('No folders available yet.') }}</p>
                        @else
                            <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4">
                                    @foreach ($folders as $folder)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label for="folder_{{ $folder->id }}" class="text-gray-700">{{ $folder->name }}</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input
                                                type="radio"
                                                id="no_access_{{ $folder->id }}"
                                                name="permissions[{{ $folder->id }}]"
                                                value="no-access"
                                                class="form-radio h-5 w-5 text-gray-500"
                                                {{ !isset($userPermissions[$folder->id]) ? 'checked' : (isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'no-access' ? 'checked' : '') }}>
                                            <label for="no_access_{{ $folder->id }}" class="text-gray-700">No access</label>

                                            <input
                                                type="radio"
                                                id="view_{{ $folder->id }}"
                                                name="permissions[{{ $folder->id }}]"
                                                value="view"
                                                class="form-radio h-5 w-5 text-blue-600"
                                                {{ isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'view' ? 'checked' : '' }}>
                                            <label for="view_{{ $folder->id }}" class="text-gray-700">View</label>

                                            <input
                                                type="radio"
                                                id="edit_{{ $folder->id }}"
                                                name="permissions[{{ $folder->id }}]"
                                                value="edit"
                                                class="form-radio h-5 w-5 text-blue-600"
                                                {{ isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'edit' ? 'checked' : '' }}>
                                            <label for="edit_{{ $folder->id }}" class="text-gray-700">Edit</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-start mt-6">
                                    <x-primary-button class="ml-0">
                                        {{ __('Save Permissions') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        @endif

                        </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>