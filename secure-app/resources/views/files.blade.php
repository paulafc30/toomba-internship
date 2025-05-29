<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Toomba Secure</title>

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap Icons CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Panel Izquierdo: Upload --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="w-full h-28 bg-contain bg-center bg-no-repeat mb-6"
                        style="background-image: url('https://cdn-icons-png.freepik.com/512/6700/6700085.png');"></div>

                    @isset($folder)
                    @php
                    $userPermission = null;
                    if (Auth::check()) {
                    $userPermission = Auth::user()->user_type === 'administrator'
                    ? 'edit'
                    : \App\Models\Permission::where('user_id', Auth::id())
                    ->where('folder_id', $folder->id)
                    ->value('permission_type');
                    }
                    @endphp

                    @if (Auth::check() && $userPermission === 'edit')
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-4">{{ __('Upload files') }}</h2>

                        @if (session('success'))
                        <div id="success-message" class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
                        @endif

                        <div class="flex flex-col space-y-3">
                            <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center text-gray-600 hover:bg-gray-100 cursor-pointer">
                                {{ __('Drag and drop a file here or click to select') }}
                            </div>

                            <input type="file" name="file" id="fileInput" class="hidden" />
                            <input type="text" id="fileName" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm" placeholder="{{ __('No file selected') }}" />
                            <button type="button" id="uploadButton" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700" disabled>
                                {{ __('Upload File') }}
                            </button>
                        </div>
                    </div>
                    @endif
                    @endisset
                </div>

                {{-- Panel derecho: File List --}}
                <div class="bg-white shadow-sm sm:rounded-lg col-span-1 lg:col-span-3 p-6 text-gray-900">
                    <h1 class="text-lg font-semibold mb-4">{{ __('Files List') }}</h1>

                    {{-- Barra de búsqueda --}}
                    <form method="GET" action="{{ isset($folder) ? route('admin.folders.files', $folder->id) : route('files.view') }}" class="flex flex-col sm:flex-row items-center gap-3 mb-4">
                        <input type="text" id="searchInput" name="search" value="{{ old('search', $query ?? '') }}" placeholder="Search for file by name" class="w-full sm:w-auto flex-grow px-4 py-2 border border-gray-300 rounded-md text-sm" />
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-400">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                    </form>

                    {{-- Lista de archivos --}}
                    <ul id="fileList" class="divide-y divide-gray-200 mb-4">
                        @if (isset($files) && $files->isNotEmpty())
                        @foreach ($files as $file)
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $file->name }}</span>
                            <div class="flex gap-2">
                                <a href="{{ Storage::url($file->path) }}" target="_blank" class="bg-[#0464FA] text-white px-2 py-1 rounded hover:bg-blue-600 text-xs">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if (isset($folder))
                                @if ($userPermission === 'edit')
                                <a href="{{ route('files.download', $file->id) }}" class="text-white px-2 py-1 rounded text-xs" style="background-color: #048D6B;">
                                    <i class="bi bi-download"></i>
                                </a>

                                <form action="{{ route('admin.folders.files.destroy', ['folder' => $file->folder_id, 'file' => $file->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @elseif ($userPermission === 'view')
                                <a href="{{ route('files.download', $file->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                @else
                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                <a href="{{ route('files.download', $file->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('files.standalone.destroy', $file->name) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('files.download', $file->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                @endif
                            </div>
                        </li>
                        @endforeach
                        @else
                        <li class="text-gray-500 py-2">{{ __('No files available.') }}</li>
                        @endif
                    </ul>

                    {{-- Botón volver --}}
                    <a href="{{ route('admin.folders.index') }}" class="inline-block bg-[#1F2937] text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                        {{ __('Back to Folder List') }}
                    </a>

                </div>
            </div>
        </div>
    </x-app-layout>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');
            const fileNameInput = document.getElementById('fileName');
            const uploadButton = document.getElementById('uploadButton');
            const successMessage = document.getElementById('success-message');
            const csrfToken = '{{ csrf_token() }}';

            dropzone.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileNameInput.value = fileInput.files[0].name;
                    uploadButton.disabled = false;
                } else {
                    fileNameInput.value = '';
                    uploadButton.disabled = true;
                }
            });

            dropzone.addEventListener('dragover', e => {
                e.preventDefault();
                dropzone.classList.add('bg-gray-100');
            });

            dropzone.addEventListener('dragleave', e => {
                e.preventDefault();
                dropzone.classList.remove('bg-gray-100');
            });

            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                dropzone.classList.remove('bg-gray-100');
                const file = e.dataTransfer.files[0];
                if (file) {
                    fileInput.files = e.dataTransfer.files;
                    fileNameInput.value = file.name;
                    uploadButton.disabled = false;
                }
            });

            uploadButton.addEventListener('click', () => {
                if (fileInput.files.length === 0) {
                    alert("{{ __('Please select a file first.') }}");
                    return;
                }
                uploadFile(fileInput.files[0]);
                uploadButton.disabled = true;
            });

            function uploadFile(file) {
                const formData = new FormData();
                formData.append('file', file);

                fetch("{{ route('admin.folders.files.upload', $folder->id ?? 0) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(response => response.text())
                    .then(text => {
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.error || "{{ __('Upload failed.') }}");
                                uploadButton.disabled = false;
                            }
                        } catch (e) {
                            alert("{{ __('Upload error.') }}");
                            uploadButton.disabled = false;
                        }
                    })
                    .catch(() => {
                        alert("{{ __('Upload error.') }}");
                        uploadButton.disabled = false;
                    });
            }

            const clearBtn = document.getElementById('clear-search');
            const searchInput = document.getElementById('searchInput');
            clearBtn.addEventListener('click', () => {
                if (searchInput.value !== '') {
                    searchInput.value = '';
                    searchInput.form.submit();
                }
            });

            searchInput.addEventListener('input', () => {
                if (searchInput.value === '') {
                    searchInput.form.submit();
                }
            });

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>