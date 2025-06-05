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
            <?php echo e(__('Send Upload Link')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <?php if($errors->any()): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form id="sendLinkForm" method="POST" action="<?php echo e(route('admin.temporary-link.store-upload')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-semibold mb-2"><?php echo e(__('Name')); ?></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo e(old('name')); ?>"
                            required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-semibold mb-2"><?php echo e(__('Email Address')); ?></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo e(old('email')); ?>"
                            required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <fieldset class="mb-6">
                        <legend class="text-gray-700 font-semibold mb-4"><?php echo e(__('Privacy Options')); ?></legend>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-semibold mb-1"><?php echo e(__('Enter a Password (optional)')); ?></label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                value="<?php echo e(old('password')); ?>"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <small class="text-gray-500"><?php echo e(__('Leave blank for no password.')); ?></small>
                        </div>

                        <div class="mb-4">
                            <label for="expire_date" class="block text-gray-700 text-sm font-semibold mb-1"><?php echo e(__('Link Expire Date')); ?></label>
                            <input
                                type="date"
                                id="expire_date"
                                name="expire_date"
                                value="<?php echo e(old('expire_date')); ?>"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <small class="text-gray-500"><?php echo e(__('Optional. If not set, it will expire after 7 days.')); ?></small>
                        </div>

                        <div>
                            <label for="expire_time" class="block text-gray-700 text-sm font-semibold mb-1"><?php echo e(__('Link Expire Time')); ?></label>
                            <input
                                type="time"
                                id="expire_time"
                                name="expire_time"
                                value="<?php echo e(old('expire_time', '23:59')); ?>"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <small class="text-gray-500"><?php echo e(__('Optional. Defaults to 23:59.')); ?></small>
                        </div>
                    </fieldset>

                    <div class="flex justify-end gap-4 mt-6">
                        <a href="<?php echo e(route('dashboard')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-[#1F2937] hover:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                            <?php echo e(__('Back to Dashboard')); ?>

                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-[#0464FA] hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                            <?php echo e(__('Send Upload Link')); ?>

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
        window.location.href = "<?php echo e(route('dashboard')); ?>";
    }
</script><?php /**PATH /var/www/html/resources/views/admin/temporary-link.blade.php ENDPATH**/ ?>