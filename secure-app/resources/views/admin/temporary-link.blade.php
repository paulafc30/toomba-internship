<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Send Upload Link') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="sendLinkForm" method="POST" action="{{ route('admin.temporary-link.store-upload') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-semibold mb-2">{{ __('Name') }}</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">{{ __('Email Address') }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <fieldset class="mb-6">
                        <legend class="text-gray-700 font-semibold mb-4">{{ __('Privacy Options') }}</legend>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-semibold mb-1">{{ __('Enter a Password (optional)') }}</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                value="{{ old('password') }}"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <small class="text-gray-500">{{ __('Leave blank for no password.') }}</small>
                        </div>

                        <div class="mb-4">
                            <label for="expire_date" class="block text-gray-700 text-sm font-semibold mb-1">{{ __('Link Expire Date') }}</label>
                            <input
                                type="date"
                                id="expire_date"
                                name="expire_date"
                                value="{{ old('expire_date') }}"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <small class="text-gray-500">{{ __('Optional. If not set, it will expire after 7 days.') }}</small>
                        </div>

                        <div>
                            <label for="expire_time" class="block text-gray-700 text-sm font-semibold mb-1">{{ __('Link Expire Time') }}</label>
                            <input
                                type="time"
                                id="expire_time"
                                name="expire_time"
                                value="{{ old('expire_time', '23:59') }}"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <small class="text-gray-500">{{ __('Optional. Defaults to 23:59.') }}</small>
                        </div>
                    </fieldset>

                    <div class="flex justify-end gap-4 mt-6">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-4 py-2 bg-[#1F2937] hover:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                            {{ __('Back to Dashboard') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-[#0464FA] hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                            {{ __('Send Upload Link') }}
                        </button>
                    </div>
                </form>

                <div id="success-popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #f9f9f9; border: 1px solid #ccc; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index: 1000;">
                    <p style="color: black;" id="success-message"></p>
                    <button onclick="redirectToDashboard()" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 mt-4">OK</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sendLinkForm = document.getElementById('sendLinkForm');
        const successPopup = document.getElementById('success-popup');
        const successMessage = document.getElementById('success-message');

        sendLinkForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const form = this;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.textContent = data.success;
                    successPopup.style.display = 'block';
                } else if (data.errors) {
                    // Handle validation errors if needed
                    console.error('Validation errors:', data.errors);
                    // Display errors in the page (you might want to add a specific error display area)
                } else {
                    console.error('Error:', data);
                    // Display a generic error message
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                // Display a generic error message
            });
        });
    });

    function redirectToDashboard() {
        window.location.href = "{{ route('dashboard') }}";
    }
</script>