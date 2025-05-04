<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Permissions</title>
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

                        <!-- User Info Section -->
                        <h3 class="text-lg font-semibold mb-4">{{ __('Editing Permissions for: ') }}{{ $user->name }}</h3>

                        <!-- Folder Permissions Section -->
                        <h3 class="text-lg font-semibold mb-4">{{ __('Folder Permissions') }}</h3>
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
                                            id="view_{{ $folder->id }}"
                                            name="permissions[{{ $folder->id }}]"
                                            value="view"
                                            class="form-radio h-5 w-5 text-blue-600"
                                            {{ isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'view' ? 'checked' : ( !isset($userPermissions[$folder->id]) ? 'checked' : '' ) }}>
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

                        <!-- Back Button Section 
                        <div class="flex items-center justify-end mt-4">
                            <<x-primary-button
                                onclick="window.location='{{ route('admin.users') }}';"
                                class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                {{ __('Back to User List') }}
                                </x-primary-button>
                        </div>-->


                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>