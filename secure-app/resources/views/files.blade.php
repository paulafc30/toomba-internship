<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Files') }}
                @isset($folder)
                {{ __('in Folder:') }} {{ $folder->name }}
                @endisset
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Upload Block --}}
                        @isset($folder)
                        <div class="bg-white shadow sm:rounded-lg p-6 mb-8 w-full">
                            <h2 class="text-lg font-semibold mb-4">{{ __('Upload files') }} ({{ $folder->name }})</h2>

                            @if (session('success'))
                            <div class="alert alert-success mb-4">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('admin.folders.files.upload', $folder->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex flex-col sm:flex-row items-stretch gap-2">
                                    <input type="file" name="file" id="fileInput" class="hidden" required onchange="updateFileName()">
                                    <label for="fileInput" class="cursor-pointer inline-block px-4 py-2 border border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 text-sm">
                                        {{ __('Select files') }}
                                    </label>
                                    <input type="text" class="flex-1 border border-gray-300 rounded-md px-4 py-2 text-sm" id="fileName" placeholder="{{ __('No file selected') }}" readonly>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">{{ __('Upload File') }}</button>
                                </div>
                            </form>
                        </div>
                        @endisset

                        {{-- File List Block --}}
                        <div class="bg-white shadow sm:rounded-lg p-6 w-full">
                            <h1 class="text-lg font-semibold mb-4">{{ __('Files List') }}</h1>
                            <ul class="list-group mb-4">
                                @if (isset($files) && $files->isNotEmpty())
                                @foreach($files as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $file['name'] }}
                                    <div>
                                        <a href="{{ Storage::url($file['path']) }}" target="_blank" class="btn btn-sm btn-info">{{ __('View') }}</a>
                                        <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-success ml-2">{{ __('Download') }}</a>

                                        @if ($file['folder_id'] === null)
                                        <form action="{{ route('files.destroy.standalone', $file['name']) }}" method="POST" class="d-inline ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this file?') }}')">{{ __('Delete') }}</button>
                                        </form>
                                        @else
                                        @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                        <form action="{{ route('admin.folders.files.destroy', ['folder' => $file['folder_id'], 'file' => $file['id']]) }}" method="POST" class="d-inline ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this file?') }}')">{{ __('Delete') }}</button>
                                        </form>
                                        @elseif (Auth::check() && Auth::user()->user_type === 'client')
                                        <form action="{{ route('client.files.destroy', $file['id']) }}" method="POST" class="d-inline ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this file?') }}')">{{ __('Delete') }}</button>
                                        </form>
                                        @endif
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                                @else
                                <li class="list-group-item">{{ __('No files available.') }}</li>
                                @endif
                            </ul>

                            <a href="{{ route('admin.folders.index') }}" class="btn btn-secondary">{{ __('Back to Folder List') }}</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </x-app-layout>
    <script>
        function updateFileName() {
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileName');

            if (fileInput.files.length > 0) {
                fileNameDisplay.value = fileInput.files[0].name;
            } else {
                fileNameDisplay.value = '{{ __("No file selected") }}';
            }
        }
    </script>
</body>

</html>