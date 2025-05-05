<x-jet-action-section>
    <x-slot name="title">
        {{ __('Two Factor Authentication') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add additional security to your account using two factor authentication.') }}
    </x-slot>

    <x-slot name="content">
        @if (! auth()->user()->two_factor_secret)
            <p>{{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s authenticator application.') }}</p>

            <x-jet-confirms-password wire:then="enableTwoFactorAuthentication">
                <x-jet-button wire:loading.attr="disabled">
                    {{ __('Enable') }}
                </x-jet-button>
            </x-jet-confirms-password>
        @else
            <p>{{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s authenticator application.') }}</p>

            @if (session('status') == 'two-factor-authentication-enabled')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('Two factor authentication has been enabled. Now scan the following QR code using your phone\'s authenticator application and enter the generated code.') }}
                </div>

                <div class="mt-4" x-data="{ recovery: false }">
                    <div class="mb-4" x-show="! recovery">
                        {!! auth()->user()->twoFactorQrCodeSvg() !!}
                    </div>

                    <div class="mt-4" x-show="! recovery">
                        <p class="font-semibold">
                            {{ __('Setup Key:') }}
                        </p>

                        <p>{{ decrypt(auth()->user()->two_factor_secret) }}</p>
                    </div>

                    <div class="mt-4" x-show="recovery">
                        <p class="font-semibold">
                            {{ __('Recovery Codes:') }}
                        </p>

                        <ul class="list-disc list-inside">
                            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                <li>{{ $code }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4">
                        <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" @click="recovery = ! recovery">
                            {{ __('Show Recovery Codes') }}
                        </button>
                    </div>
                </div>
            @endif

            <x-jet-confirms-password wire:then="disableTwoFactorAuthentication">
                <x-jet-danger-button class="mt-4" wire:loading.attr="disabled">
                    {{ __('Disable') }}
                </x-jet-danger-button>
            </x-jet-confirms-password>
        @endif
    </x-slot>
</x-jet-action-section>