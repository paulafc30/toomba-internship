<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Permissions') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Editing Permissions for: ') }}{{ $user->name }}</h3>

                        <div class="mb-4">
                            <img src="{{ asset('resources/img/user.jpg') }}" alt="Imagen de usuario">
                            <p><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                            <p><strong>{{ __('User Type:') }}</strong> {{ $user->user_type }}</p>
                        </div>

                        <h3 class="text-lg font-semibold mb-4">{{ __('Folder Permissions') }}</h3>
                        <div>
                            <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}">
                                @csrf
                                @method('PUT')

                                @foreach ($folders as $folder)
                                <div class="mb-4">
                                    <label for="folder_{{ $folder->id }}" class="inline-flex items-center">
                                        <input
                                            type="checkbox"
                                            id="folder_{{ $folder->id }}"
                                            name="folders[]"
                                            value="{{ $folder->id }}"
                                            class="form-checkbox h-5 w-5 text-blue-600"
                                            {{ in_array($folder->id, $userPermissions) ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700">{{ $folder->name }}</span>
                                    </label>
                                </div>
                                @endforeach

                                <div class="flex items-center justify-start mt-4">
                                    <x-primary-button class="ml-0">
                                        {{ __('Save Permissions') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <!--<x-primary-button onclick="window.location='{{ route('admin.users') }}';">
                            {{ __('Back to User List') }}
                        </x-primary-button>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>