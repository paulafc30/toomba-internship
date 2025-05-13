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
                {{ __('User Information') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <h3 class="text-lg font-semibold mb-4">{{ __('Information for: ') }}{{ $user->name }}</h3>

                        <div class="flex items-center space-x-4 mb-6">
                            @if ($user->profile_image_path)
                                <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="User image" class="rounded-full w-20 h-20 object-cover">
                            @else
                                <img src="../resources/img/user.jpg" alt="User image" class="rounded-full w-20 h-20 object-cover">
                            @endif
                            <div>
                                <p><strong>{{ __('Name:') }}</strong> {{ $user->name }}</p>
                                <p><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                                <p><strong>{{ __('User Type:') }}</strong> {{ $user->user_type }}</p>
                                <p><strong>{{ __('Registration Date:') }}</strong> {{ $user->created_at }}</p>
                                <p><strong>{{ __('Updated Date:') }}</strong> {{ $user->updated_at }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <<button onclick="window.location='{{ route('admin.users') }}';" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                {{ __('Back to User List') }}
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
