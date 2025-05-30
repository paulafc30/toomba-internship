<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toomba Secure</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

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
                <?php echo e(__('Edit Permissions')); ?>

            </h2>
         <?php $__env->endSlot(); ?>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md sm:rounded-lg p-6">

                    <h3 class="text-lg font-semibold mb-6">
                        <?php echo e(__('Editing Permissions for: ')); ?> <span class="font-normal"><?php echo e($user->name); ?></span>
                    </h3>

                    <h3 class="text-lg font-semibold mb-4"><?php echo e(__('Folder Permissions')); ?></h3>

                    <?php if($folders->isEmpty()): ?>
                    <p class="text-gray-600"><?php echo e(__('No folders available yet.')); ?></p>
                    <?php else: ?>
                    <form method="POST" action="<?php echo e(route('admin.users.update-permissions', $user)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="space-y-6">
                            <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b pb-4">
                                <label for="folder_<?php echo e($folder->id); ?>" class="flex items-center text-gray-800 font-medium mb-2 md:mb-0">
                                    <img src="<?php echo e(asset('images/folder-icon.png')); ?>" alt="Folder Icon" class="w-5 h-5 mr-2">
                                    <?php echo e($folder->name); ?>

                                </label>


                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input
                                            type="radio"
                                            id="no_access_<?php echo e($folder->id); ?>"
                                            name="permissions[<?php echo e($folder->id); ?>]"
                                            value="no-access"
                                            class="text-gray-500 focus:ring-gray-400"
                                            <?php echo e(!isset($userPermissions[$folder->id]) || $userPermissions[$folder->id] === 'no-access' ? 'checked' : ''); ?>>
                                        <span class="ml-2 text-sm text-gray-700">No access</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input
                                            type="radio"
                                            id="view_<?php echo e($folder->id); ?>"
                                            name="permissions[<?php echo e($folder->id); ?>]"
                                            value="view"
                                            class="text-blue-600 focus:ring-blue-500"
                                            <?php echo e(isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'view' ? 'checked' : ''); ?>>
                                        <span class="ml-2 text-sm text-gray-700">View</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input
                                            type="radio"
                                            id="edit_<?php echo e($folder->id); ?>"
                                            name="permissions[<?php echo e($folder->id); ?>]"
                                            value="edit"
                                            class="text-green-600 focus:ring-green-500"
                                            <?php echo e(isset($userPermissions[$folder->id]) && $userPermissions[$folder->id] === 'edit' ? 'checked' : ''); ?>>
                                        <span class="ml-2 text-sm text-gray-700">Edit</span>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                            <a href="<?php echo e(route('dashboard')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-[#0464FA] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <?php echo e(__('Back to Dashboard')); ?>

                            </a>
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full sm:w-auto']); ?>
                                <?php echo e(__('Save Permissions')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>

                        </div>
                    </form>
                    <?php endif; ?>

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

</html><?php /**PATH /var/www/html/resources/views/admin/edit-permissions.blade.php ENDPATH**/ ?>