<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        .sidebar-image {
            background-image: url('https://cdn-icons-png.freepik.com/512/6700/6700085.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100px;
            margin-bottom: 1rem;
        }

        /* Mejora la presentación de los botones dentro de cada archivo */
        .file-actions>* {
            margin-left: 0.5rem;
        }

        /* Oculta el input file nativo */
        #fileInput {
            display: none;
        }

        /* Personaliza el botón de selección */
        .file-label {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
        }

        .file-label:hover {
            background-color: #e2e6ea;
        }

        /* Botón de subir */
        .upload-btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
        }

        .upload-btn:hover {
            background-color: #0b5ed7;
        }
    </style>

    </style>
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="sidebar-image">

                    </div>

                    @isset($folder)
                    @php
                    $userPermission = Auth::user() ? \App\Models\Permission::where('user_id', Auth::id())->where('folder_id', $folder->id)->value('permission_type') : null;
                    @endphp

                    @if ($userPermission === 'edit')
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-4">{{ __('Upload files') }} ({{ $folder->name }})</h2>

                        @if (session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('admin.folders.files.upload', $folder->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-col space-y-3 w-full max-w-md">
                                <!-- Botón para seleccionar archivo -->
                                <label for="fileInput"
                                    class="cursor-pointer px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm text-center hover:bg-gray-200">
                                    {{ __('Select files') }}
                                </label>
                                <input type="file" name="file" id="fileInput" class="hidden" required onchange="updateFileName()">

                                <!-- Nombre del archivo seleccionado -->
                                <input type="text" id="fileName" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm"
                                    placeholder="{{ __('No file selected') }}">

                                <!-- Botón de subir archivo -->
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition">
                                    {{ __('Upload File') }}
                                </button>
                            </div>



                        </form>
                    </div>
                    @endif
                    @endisset
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1 lg:col-span-3 p-6 text-gray-900">
                    <h1 class="text-lg font-semibold mb-4">{{ __('Files List') }}</h1>
                    <ul class="list-group mb-4">
                        @if (isset($files) && $files->isNotEmpty())
                        @foreach($files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $file['name'] }}
                            <div class="file-actions d-flex align-items-center">
                                <a href="{{ Storage::url($file['path']) }}" target="_blank" class="btn btn-sm btn-info">{{ __('View') }}</a>
                                @if ($folder)
                                @if ($userPermission === 'edit')
                                <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-success ml-2">{{ __('Download') }}</a>
                                <form action="{{ route('admin.folders.files.destroy', ['folder' => $file['folder_id'], 'file' => $file['id']]) }}" method="POST" class="d-inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">{{ __('Delete') }}</button>
                                </form>
                                @elseif ($userPermission === 'view')
                                <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-success ml-2">{{ __('Download') }}</a>
                                @endif
                                @else
                                @if (Auth::check() && Auth::user()->user_type === 'administrator')
                                <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-success ml-2">{{ __('Download') }}</a>
                                <form action="{{ route('files.standalone.destroy', $file['name']) }}" method="POST" class="d-inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">{{ __('Delete') }}</button>
                                </form>
                                @else
                                <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-success ml-2">{{ __('Download') }}</a>
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