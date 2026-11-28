<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"><title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> · CampusCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/app.css')); ?>?v=20261026-redesign1" rel="stylesheet">
</head>
<body class="app-page">
<div class="app-shell">
    <aside class="sidebar <?php echo e(auth()->check() && auth()->user()->isAdmin() ? 'admin-sidebar' : ''); ?>" id="sidebar">
        <a class="sidebar-brand-logo" href="<?php echo e(route('dashboard')); ?>" aria-label="CampusCare dashboard"><img src="<?php echo e(asset('images/marwadi-university-logo-naac.png')); ?>?v=1" alt="Marwadi University"></a>
        <div class="sidebar-brand-label">CAMPUS OPERATIONS</div>
        <?php if(auth()->guard()->check()): ?>
        <div class="nav-label"><?php echo e(auth()->user()->isAdmin() ? 'ADMINISTRATION' : (auth()->user()->isStaff() ? 'STAFF WORKSPACE' : 'CAMPUS PORTAL')); ?></div>
        <nav class="side-nav">
            <a href="<?php echo e(route('dashboard')); ?>" class="side-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"><i class="bi bi-grid-1x2"></i> <?php echo e(auth()->user()->isStaff()?'My tasks':(auth()->user()->isCampusUser()?'My requests':'Dashboard')); ?></a>
            <?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('admin.records.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.records.*') ? 'active' : ''); ?>"><i class="bi bi-clipboard2-check"></i> Cleaning tasks</a>
                <a href="<?php echo e(route('admin.requests.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.requests.*') ? 'active' : ''); ?>"><i class="bi bi-inbox"></i> Requests</a>
                <a href="<?php echo e(route('admin.reports.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>"><i class="bi bi-bar-chart"></i> Reports</a>
                <a href="<?php echo e(route('admin.staff.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.staff.*') ? 'active' : ''); ?>"><i class="bi bi-people"></i> Staff</a>
                <a href="<?php echo e(route('admin.locations.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.locations.*') ? 'active' : ''); ?>"><i class="bi bi-geo"></i> Locations</a>
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>"><i class="bi bi-tags"></i> Categories</a>
                <a href="<?php echo e(route('admin.priorities.index')); ?>" class="side-link <?php echo e(request()->routeIs('admin.priorities.*') ? 'active' : ''); ?>"><i class="bi bi-flag"></i> Priorities</a>
            <?php elseif(auth()->user()->isCampusUser()): ?>
                <a href="<?php echo e(route('requests.create')); ?>" class="side-link <?php echo e(request()->routeIs('requests.create') ? 'active' : ''); ?>"><i class="bi bi-plus-circle"></i> Submit a request</a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-bottom"><div class="profile"><div class="avatar"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></div><a href="<?php echo e(route('profile')); ?>" class="profile-link"><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e(str_replace('_',' ',ucfirst(auth()->user()->role))); ?> · Profile</small></a></div></div>
        <?php endif; ?>
    </aside>
    <main class="main-area">
        <header class="topbar"><a class="topbar-logo" href="<?php echo e(route('dashboard')); ?>" aria-label="Marwadi University dashboard"><img src="<?php echo e(asset('images/marwadi-university-logo-naac.png')); ?>?v=1" alt="Marwadi University"></a><button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false" aria-controls="sidebar"><i class="bi bi-list"></i></button><div class="breadcrumb-text"><span class="breadcrumb-section">Campus operations</span><span class="breadcrumb-separator">/</span><span class="breadcrumb-current"><?php echo $__env->yieldContent('crumb', 'Overview'); ?></span></div><div class="top-actions"><span class="today-label"><i class="bi bi-calendar3"></i> <?php echo e(now()->format('D, M j')); ?></span><form method="POST" action="<?php echo e(route('logout')); ?>" class="top-logout-form"><?php echo csrf_field(); ?><button class="top-signout" type="submit" aria-label="Sign out"><i class="bi bi-box-arrow-right"></i><span>Sign out</span></button></form><span class="top-avatar">CC</span></div></header>
        <section class="page-content">
            <?php if(session('success')): ?><div class="alert alert-success alert-dismissible fade show app-notice" role="status"><span class="notice-icon"><i class="bi bi-check-lg"></i></span><span><?php echo e(session('success')); ?></span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss"></button></div><?php endif; ?>
            <?php if($errors->any()): ?><div class="alert alert-danger alert-dismissible fade show app-notice" role="alert"><span class="notice-icon"><i class="bi bi-exclamation-lg"></i></span><div><strong>Please review the form.</strong><ul class="mb-0 mt-2"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss"></button></div><?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </section>
        
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="<?php echo e(asset('js/app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body></html>
<?php /**PATH C:\laragon\www\campus-cleaning\resources\views\layouts\app.blade.php ENDPATH**/ ?>