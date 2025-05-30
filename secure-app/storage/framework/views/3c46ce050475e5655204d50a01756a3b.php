<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-image {
            background-image: url('https://static.vecteezy.com/system/resources/previews/000/440/965/non_2x/vector-folder-icon.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 150px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="bg-gray-50">
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
                <?php echo e(__('Folder List')); ?>

            </h2>
         <?php $__env->endSlot(); ?>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col lg:flex-row lg:space-x-6 space-y-6 lg:space-y-0">
                            <div class="w-full lg:w-1/4 p-4 rounded-lg shadow-md bg-white">
                                <div class="sidebar-image rounded-lg"></div>

                                <?php if(Auth::check() && Auth::user()->user_type === 'administrator'): ?>
                                <a href="<?php echo e(route('admin.folders.create')); ?>"
                                    class="inline-flex items-center justify-center px-4 py-2  bg-[#0464FA] hover:bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest w-full transition duration-150 ease-in-out">
                                    <?php echo e(__('Create New Folder')); ?>

                                </a>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1">
                                <?php if(session('success')): ?>
                                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-4">
                                    <?php echo e(session('success')); ?>

                                </div>
                                <?php endif; ?>

                                
                                <form method="GET" action="<?php echo e(route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index')); ?>" class="mb-4">
                                    <div class="flex w-full">
                                        <input
                                            type="text"
                                            name="search"
                                            id="search-input"
                                            value="<?php echo e(request('search')); ?>"
                                            placeholder="Buscar carpeta..."
                                            class="form-control w-full rounded-l border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">
                                            Buscar
                                        </button>
                                        <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-r text-sm hover:bg-gray-400">
                                            <i class="bi bi-x-circle"></i> Clear
                                        </button>
                                    </div>
                                </form>

                                <?php if($folders->isEmpty()): ?>
                                <p class="text-gray-600">
                                    <?php echo e(request('search') ? __('No se encuentra la carpeta.') : (Auth::check() && Auth::user()->user_type === 'administrator' ? __('There are no folders registered yet.') : __('You do not have access to any folders yet.'))); ?>

                                </p>
                                <?php else: ?>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <!--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?php echo e(__('Name')); ?>

                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?php echo e(__('Updated At')); ?>

                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?php echo e(__('Actions')); ?>

                                            </th>-->
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                                <a href="<?php echo e(Auth::user()->user_type === 'administrator' ? route('admin.folders.files', $folder->id) : route('client.folders.files', $folder->id)); ?>"
                                                    class="hover:underline">
                                                    <?php echo e($folder->name); ?>

                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <?php echo e($folder->updated_at->format('d/m/Y H:i')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <?php if(Auth::user()->user_type === 'administrator'): ?>
                                                <a href="<?php echo e(route('admin.folders.edit', $folder->id)); ?>"
                                                    class="inline-flex items-center px-4 py-2 bg-[#0464FA] hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-150 ease-in-out">
                                                    <?php echo e(__('Rename')); ?>

                                                </a>

                                                <form action="<?php echo e(route('admin.folders.destroy', $folder->id)); ?>"
                                                    method="POST" class="inline-block ml-2">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-150 ease-in-out"
                                                        onclick="return confirm('Are you sure you want to delete this folder?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    <?php echo e($folders->links()); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="<?php echo e(route('dashboard')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-[#1F2937] hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                <?php echo e(__('Back to Dashboard')); ?>

                            </a>
                        </div>
                    </div>
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

    <script>
        const searchInput = document.getElementById('search-input');
        const baseRoute = "<?php echo e(route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index')); ?>";

        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                window.location.href = baseRoute;
            }
        });

        document.getElementById('clear-search').addEventListener('click', function() {
            searchInput.value = '';
            window.location.href = baseRoute;
        });

        setTimeout(function() {
            var message = document.getElementById('success-message');
            if (message) {
                message.style.display = 'none';
            }
        }, 3000); // 3000 milisegundos = 3 segundos

    </script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
</body>

</html><?php /**PATH /var/www/html/resources/views/folders.blade.php ENDPATH**/ ?>