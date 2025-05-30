<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Welcome, <?php echo e(auth()->user()->name); ?>!</h2>
                <div>
                    
                </div>
            </div>
         <?php $__env->endSlot(); ?>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-4 gap-4">

                    <aside class="col-span-1 bg-gray-800 text-white rounded-lg shadow-md p-4">
                        <nav>
                            <ul class="space-y-4">
                                <?php if(auth()->user()->user_type === 'administrator'): ?>
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="<?php echo e(route('admin.users')); ?>" class="block px-4 py-2 rounded hover:bg-gray-600 <?php echo e(request()->routeIs('admin.users') ? 'bg-gray-600' : ''); ?>">
                                            Manage Users
                                        </a>
                                    </li>
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="<?php echo e(route('admin.folders.index')); ?>" class="block px-4 py-2 rounded hover:bg-gray-600 <?php echo e(request()->routeIs('admin.folders.*') ? 'bg-gray-600' : ''); ?>">
                                            Manage Folders
                                        </a>
                                    </li>
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="<?php echo e(route('admin.temporary-link.index')); ?>" class="block px-4 py-2 rounded hover:bg-gray-600 <?php echo e(request()->routeIs('admin.temporary-link.*') ? 'bg-gray-600' : ''); ?>">
                                            Send Temporary Link
                                        </a>
                                    </li>
                                <?php elseif(auth()->user()->user_type === 'client' || auth()->user()->user_type === 'temporary'): ?>
                                    <li class="transition transform hover:scale-105 duration-200 ease-in-out">
                                        <a href="<?php echo e(route('client.folders.index')); ?>" class="block px-4 py-2 rounded hover:bg-gray-600 <?php echo e(request()->routeIs('client.folders.*') ? 'bg-gray-600' : ''); ?>">
                                            View My Folders
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </aside>

                    <main class="col-span-3 bg-white p-6 rounded-lg shadow-md">
                        <?php if(auth()->user()->user_type === 'administrator'): ?>
                            <?php if(request()->routeIs('admin.folders.*')): ?>
                                <?php echo $__env->make('admin.folders.index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                            <?php else: ?>
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <h1 class="text-xl font-semibold mb-4">Administrator Panel</h1>
                                    <h2>Administrator Features:</h2>
                                    <p>Select an option from the left menu.</p>
                                </div>
                            <?php endif; ?>
                        <?php elseif(auth()->user()->user_type === 'client' || auth()->user()->user_type === 'temporary'): ?>
                            <?php if(request()->routeIs('client.folders.*')): ?>
                                <?php echo $__env->make('client.folders.index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                            <?php else: ?>
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                    <h1 class="text-xl font-semibold mb-4">Client Area</h1>
                                    <p class="mb-2">Welcome, <?php echo e(auth()->user()->name); ?>!</p>
                                    <p>You can view your folders using the menu on the left.</p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </main>
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

    <?php $__env->startPush('styles'); ?>
    <style>
        /* Additional Custom Styles */
        .font-semibold {
            font-weight: bold;
        }

        .hover\:bg-gray-600:hover {
            background-color: #4B5563;
        }

        /* Active Link Style */
        .bg-gray-600 {
            background-color: #4B5563;
        }

        /* Sidebar Items Transition */
        nav a {
            transition: all 0.2s ease-in-out;
        }
    </style>
    <?php $__env->stopPush(); ?>

</body>

</html><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>