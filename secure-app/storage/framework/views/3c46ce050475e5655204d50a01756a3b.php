<?php echo app('Illuminate\Foundation\Vite')('resources/js/folders.js'); ?>
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

                            
                            <form method="GET" action="<?php echo e(route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index')); ?>" class="space-y-4">

                                
                                <div class="flex w-full gap-2">
                                    <input
                                        type="text"
                                        name="search"
                                        id="search-input"
                                        value="<?php echo e(request('search')); ?>"
                                        placeholder="Buscar carpeta..."
                                        class="w-full rounded-full border border-gray-300 shadow-sm focus:border-[#1D4ED8] focus:ring-[#1D4ED8] px-4 py-1 text-sm" />

                                    <button type="submit" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 transition">
                                        Search
                                    </button>

                                    <button type="button" id="clear-search" class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition">
                                        <i class="bi bi-x-circle"></i>
                                    </button>

                                    <button type="button" id="toggle-filters" class="bg-[#1D4ED8] text-white px-4 py-1 text-sm rounded-full hover:bg-blue-700 flex items-center gap-1 transition">
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

                                        <a href="<?php echo e(route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index')); ?>"
                                            class="bg-gray-300 text-gray-800 px-4 py-1 text-sm rounded-full hover:bg-gray-400 transition flex items-center gap-1">
                                            <i class="bi bi-x-circle"></i> Clean filters
                                        </a>
                                    </div>
                                </div>
                            </form>


                            <?php if($folders->isEmpty()): ?>
                            <p class="text-gray-600">
                                <?php echo e(request('search') ? __('The folder cannot be found.') : (Auth::check() && Auth::user()->user_type === 'administrator' ? __('There are no folders registered yet.') : __('You do not have access to any folders yet.'))); ?>

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
<?php endif; ?><?php /**PATH /var/www/html/resources/views/folders.blade.php ENDPATH**/ ?>