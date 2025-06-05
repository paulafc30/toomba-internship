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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('User Information')); ?>

            </h2>
         <?php $__env->endSlot(); ?>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <h3 class="text-lg font-semibold mb-4"><?php echo e(__('Information for: ')); ?><?php echo e($user->name); ?></h3>

                        <div class="flex items-center space-x-4 mb-6">
                            <?php if($user->profile_image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $user->profile_image_path)); ?>" alt="User image" class="rounded-full w-20 h-20 object-cover">
                            <?php else: ?>
                                <img src="../resources/img/user.jpg" alt="User image" class="rounded-full w-20 h-20 object-cover">
                            <?php endif; ?>
                            <div>
                                <p><strong><?php echo e(__('Name:')); ?></strong> <?php echo e($user->name); ?></p>
                                <p><strong><?php echo e(__('Email:')); ?></strong> <?php echo e($user->email); ?></p>
                                <p><strong><?php echo e(__('User Type:')); ?></strong> <?php echo e($user->user_type); ?></p>
                                <p><strong><?php echo e(__('Registration Date:')); ?></strong> <?php echo e($user->created_at); ?></p>
                                <p><strong><?php echo e(__('Updated Date:')); ?></strong> <?php echo e($user->updated_at); ?></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <<button onclick="window.location='<?php echo e(route('admin.users')); ?>';" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <?php echo e(__('Back to User List')); ?>

                            </button>
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
</body>

</html>
<?php /**PATH /var/www/html/resources/views/admin/show-info.blade.php ENDPATH**/ ?>