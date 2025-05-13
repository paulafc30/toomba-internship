<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
</head>
<body>
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Please configure two-factor authentication to continue.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        @if ($twoFactorSecret)
            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    {{ __('Scan this QR code with your authenticator app:') }}
                </h3>
                <div>
                    {!! $qrCode !!}
                </div>
            </div>

            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    {{ __('Or enter this secret key manually:') }}
                </h3>
                <p class="mt-1 text-sm text-gray-600">{{ $twoFactorSecret }}</p>
            </div>

            @if ($recoveryCodes)
                <div class="mt-4">
                    <h3 class="font-semibold text-lg text-gray-800">
                        {{ __('Store these recovery codes in a secure place:') }}
                    </h3>
                    <ul class="list-disc list-inside text-sm text-gray-600">
                        @foreach ($recoveryCodes as $code)
                            <li>{{ $code }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('You can use these codes to log in if you lose access to your authenticator app.') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('register.two-factor.confirm') }}">
                @csrf

                <div class="mt-4">
                    <x-label for="code" value="{{ __('Verification Code') }}" />
                    <x-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ml-4">
                        {{ __('Continue to Dashboard') }}
                    </x-button>
                </div>
            </form>
        @else
            <p class="mt-4 text-sm text-gray-600">
                {{ __('An error occurred while generating the two-factor authentication setup.') }}
            </p>
        @endif
    </x-authentication-card>
</x-guest-layout>
</body>
</html>