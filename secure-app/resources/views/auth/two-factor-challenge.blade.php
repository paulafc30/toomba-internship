<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600" x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('Code') }}" />
                    <x-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('Recovery Code') }}" />
                    <x-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" required autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:underline cursor-pointer"
                                    x-show="! recovery"
                                    x-on:click="recovery = true; setTimeout(() => $refs.recovery_code.focus(), 25)">
                        {{ __('Use a recovery code') }}
                    </button>

                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:underline cursor-pointer"
                                    x-show="recovery"
                                    x-on:click="recovery = false; setTimeout(() => $refs.code.focus(), 25)">
                        {{ __('Use an authentication code') }}
                    </button>

                    <x-button class="ml-4">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
</body>
</html>