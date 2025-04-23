<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba</title>
</head>

<body>
    <x-guest-layout>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="user_type" :value="__('User Type')" />
                <select id="user_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" name="user_type" required onchange="toggleAdminPinField(this.value)">
                    <option value="client" @selected(old('user_type')=='client' || !old('user_type'))>{{ __('Client') }}</option>
                    <option value="administrator" @selected(old('user_type')=='administrator' )>{{ __('Administrator') }}</option>
                </select>
                <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
            </div>

            <div class="mt-4" id="admin_pin_field" style="display: none;">
                <x-input-label for="admin_pin" :value="__('Administrator PIN')" />
                <x-text-input id="admin_pin" class="block mt-1 w-full" type="password" name="admin_pin" autocomplete="off" />
                <x-input-error :messages="$errors->get('admin_pin')" class="mt-2" id="admin_pin_error" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>

    <script>
        function toggleAdminPinField(userType) {
            const adminPinField = document.getElementById('admin_pin_field');
            if (userType === 'administrator') {
                adminPinField.style.display = 'block';
            } else {
                adminPinField.style.display = 'none';
            }
        }

        // Mostrar/ocultar el campo al cargar la página basado en el valor de old('user_type')
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.getElementById('user_type');
            toggleAdminPinField(userTypeSelect.value);
        });
    </script>
</body>

</html>