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
                {{ __('Two Factor Authentication') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">
                        {{ __('Manage Two Factor Authentication') }}
                    </h3>

                    @if (session('status') == 'two-factor-authentication-enabled')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('Two factor authentication has been enabled.') }}
                    </div>
                    @endif

                    @if (session('status') == 'two-factor-authentication-disabled')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('Two factor authentication has been disabled.') }}
                    </div>
                    @endif

                    @if (! auth()->user()->two_factor_secret)
                    <p class="mb-4 text-sm text-gray-600">
                        {{ __('You have not enabled two factor authentication yet.') }}
                    </p>
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <x-primary-button>
                            {{ __('Enable') }}
                        </x-primary-button>
                    </form>
                    @else
                    <p class="mb-4 text-sm text-gray-600">
                        {{ __('Two factor authentication is currently enabled.') }}
                    </p>

                    @if (auth()->user()->recoveryCodes()->isNotEmpty())
                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-800 mb-2">
                            {{ __('Recovery Codes') }}
                        </h3>
                        <ul class="list-disc list-inside text-sm text-gray-600">
                            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                            <li>{{ $code }}</li>
                            @endforeach
                        </ul>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('Store these recovery codes in a secure place. They can be used to recover access to your account if you lose access to your two factor authentication device.') }}
                        </p>
                    </div>
                    @endif

                    <div class="mt-4 space-y-4">
                        <form method="POST" action="{{ route('two-factor.generate-recovery-codes') }}">
                            @csrf
                            <x-secondary-button>
                                {{ __('Generate New Recovery Codes') }}
                            </x-secondary-button>
                        </form>

                        <form method="POST" action="{{ route('two-factor.disable') }}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>
                                {{ __('Disable') }}
                            </x-danger-button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>