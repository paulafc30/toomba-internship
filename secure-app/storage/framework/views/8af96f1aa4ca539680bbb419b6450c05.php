<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo e(config('app.name', 'Toomba')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Tailwind CDN (opcional) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS y JS con Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js', 'resources/js/folders.js']); ?>

    <meta name="base-route" content="<?php echo e(route(Auth::user()->user_type === 'administrator' ? 'admin.folders.index' : 'client.folders.index')); ?>">

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

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="font-sans antialiased bg-gray-50">

    <div class="min-h-screen bg-gray-100">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <?php echo e($header); ?>

            </div>
        </header>
        <?php endif; ?>

        <!-- Page Content -->
        <main>
            <?php echo e($slot); ?>

        </main>
    </div>

    <!-- Bootstrap Bundle JS (con Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?> <!-- Scripts adicionales desde vistas -->
</body>

</html><?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>