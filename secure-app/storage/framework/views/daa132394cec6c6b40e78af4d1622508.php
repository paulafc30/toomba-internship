<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Toomba Secure</title>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/folders.js'); ?>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-gray-100">
    <?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('header', null, []); ?> 
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Files')); ?>

                <?php if(isset($folder)): ?>
                <?php echo e(__('in Folder:')); ?> <?php echo e($folder->name); ?>

                <?php endif; ?>
            </h2>
         <?php $__env->endSlot(); ?>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-8">

                
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="w-full h-28 bg-contain bg-center bg-no-repeat mb-6"
                        style="background-image: url('https://cdn-icons-png.freepik.com/512/6700/6700085.png');"></div>

                    <?php if(isset($folder)): ?>
                    <?php
                    $userPermission = null;
                    if (Auth::check()) {
                    $userPermission = Auth::user()->user_type === 'administrator'
                    ? 'edit'
                    : \App\Models\Permission::where('user_id', Auth::id())
                    ->where('folder_id', $folder->id)
                    ->value('permission_type');
                    }
                    ?>

                    <?php if(Auth::check() && $userPermission === 'edit'): ?>
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-4"><?php echo e(__('Upload files')); ?></h2>

                        <?php if(session('success')): ?>
                        <div id="success-message" class="bg-green-100 text-green-800 p-4 rounded mb-4"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                        <div class="bg-red-100 text-red-800 p-4 rounded mb-4"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>

                        <div class="flex flex-col space-y-3">
                            <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center text-gray-600 hover:bg-gray-100 cursor-pointer">
                                <?php echo e(__('Drag and drop a file here or click to select')); ?>

                            </div>

                            <input type="file" name="file" id="fileInput" class="hidden" />
                            <input type="text" id="fileName" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm" placeholder="<?php echo e(__('No file selected')); ?>" />
                            <button type="button" id="uploadButton" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700" disabled>
                                <?php echo e(__('Upload File')); ?>

                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>

                
                <div class="bg-white shadow-sm sm:rounded-lg col-span-1 lg:col-span-3 p-6 text-gray-900">
                    <h1 class="text-lg font-semibold mb-4"><?php echo e(__('Files List')); ?></h1>

                    
                    <form method="GET" action="<?php echo e(isset($folder) ? route('admin.folders.files', $folder->id) : route('files.view')); ?>" class="space-y-4">

                        
                        <div class="flex w-full gap-2">
                            <input
                                type="text"
                                name="search"
                                id="search-input"
                                value="<?php echo e(request('search')); ?>"
                                placeholder="Search files..."
                                class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />

                            <button type="submit" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 transition">
                                Search
                            </button>

                            <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition">
                                <i class="bi bi-x-circle"></i>
                            </button>

                            <button type="button" id="filter-button" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 flex items-center gap-1 transition">
                                <span>Filters</span>
                                <i id="filter-icon" class="bi bi-funnel"></i>
                            </button>

                        </div>

                        
                        <div id="advancedFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 hidden">

                            <div class="flex flex-col gap-1">
                                <label for="date_from" class="text-sm">From:</label>
                                <input type="date" id="date_from" name="date_from" value="<?php echo e(request('date_from')); ?>"
                                    class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="date_to" class="text-sm">To:</label>
                                <input type="date" id="date_to" name="date_to" value="<?php echo e(request('date_to')); ?>"
                                    class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />
                            </div>

                            
                            <div class="flex items-end gap-2 mt-1 md:col-span-2">
                                <button type="submit" class="bg-green-600 text-white px-4 py-1 text-sm rounded-full hover:bg-green-700 transition">
                                    Apply filters
                                </button>

                                <a href="<?php echo e(isset($folder) ? route('admin.folders.files', $folder->id) : route('files.view')); ?>"
                                    class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition flex items-center gap-1">
                                    <i class="bi bi-x-circle"></i> Clean filters
                                </a>
                            </div>
                        </div>
                    </form>



                    
                    <ul id="fileList" class="divide-y divide-gray-200 mb-4">
                        <?php if(isset($files) && $files->isNotEmpty()): ?>
                        <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex justify-between items-center py-2">
                            <span><?php echo e($file->name); ?></span>
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('files.view', $file->id)); ?>" target="_blank" class="bg-[#0464FA] text-white px-2 py-1 rounded hover:bg-blue-600 text-xs">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <?php if(isset($folder)): ?>
                                <?php if($userPermission === 'edit'): ?>
                                <a href="<?php echo e(route('files.download', $file->id)); ?>" class="text-white px-2 py-1 rounded text-xs" style="background-color: #048D6B;">
                                    <i class="bi bi-download"></i>
                                </a>

                                <form action="<?php echo e(route('admin.folders.files.destroy', ['folder' => $file->folder_id, 'file' => $file->id])); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php elseif($userPermission === 'view'): ?>
                                <a href="<?php echo e(route('files.download', $file->id)); ?>" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                <?php endif; ?>
                                <?php else: ?>
                                <?php if(Auth::check() && Auth::user()->user_type === 'administrator'): ?>
                                <a href="<?php echo e(route('files.download', $file->id)); ?>" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="<?php echo e(route('files.standalone.destroy', $file->name)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <a href="<?php echo e(route('files.download', $file->id)); ?>" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                    <i class="bi bi-download"></i>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <li class="text-gray-500 py-2"><?php echo e(__('No files available.')); ?></li>
                        <?php endif; ?>
                    </ul>

                    
                    <a href="<?php echo e(route('admin.folders.index')); ?>" class="inline-block bg-[#1F2937] text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                        <?php echo e(__('Back to Folder List')); ?>

                    </a>

                </div>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
</body>
</html><?php /**PATH /var/www/html/resources/views/files.blade.php ENDPATH**/ ?>