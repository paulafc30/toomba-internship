<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Upload Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Send Upload Link') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.temporary-link.store-upload') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">{{ __('Name') }}</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">{{ __('Email Address') }}</label>
                                <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">{{ __('Privacy Options') }}</label>
                                <div class="mb-2">
                                    <label for="password" class="block text-gray-700 text-xs font-semibold mb-1">{{ __('Enter a Password (optional)') }}</label>
                                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" value="{{ old('password') }}">
                                    <small class="text-gray-500">{{ __('Leave blank for no password.') }}</small>
                                </div>
                                <div>
                                    <label for="expire_date" class="block text-gray-700 text-xs font-semibold mb-1">{{ __('Link Expire Date') }}</label>
                                    <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="expire_date" name="expire_date" value="{{ old('expire_date') }}">
                                    <small class="text-gray-500">{{ __('Optional. If not set, it will expire after 7 days.') }}</small>
                                </div>
                                <div>
                                    <label for="expire_time" class="block text-gray-700 text-xs font-semibold mb-1">{{ __('Link Expire Time') }}</label>
                                    <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="expire_time" name="expire_time" value="{{ old('expire_time', '23:59') }}">
                                    <small class="text-gray-500">{{ __('Optional. Defaults to 23:59.') }}</small>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('Send Upload Link') }}
                                </button>
                            </div>
                        </form>

                        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded mt-4 inline-block">
                            {{ __('Back to Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>