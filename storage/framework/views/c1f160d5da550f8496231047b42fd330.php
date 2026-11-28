<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#19142d">
    <meta name="description" content="CampusCare helps campus communities submit cleaning requests and facilities teams keep every space clean, safe, and ready to learn.">
    <title><?php echo $__env->yieldContent('title','Campus cleaning, made simple'); ?> · CampusCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/app.css')); ?>?v=20261026-home-polish1" rel="stylesheet">
</head>
<body class="landing-page">
<header class="landing-nav"><div class="landing-nav-inner"><a class="landing-brand" href="<?php echo e(route('home')); ?>" aria-label="Marwadi University home"><img class="brand-logo-img landing-brand-logo" src="<?php echo e(asset('images/marwadi-university-logo-naac.png')); ?>?v=1" alt="Marwadi University"></a><nav class="landing-nav-links"><a href="#how-it-works">How it works</a><a href="#features">Features</a><a class="landing-login" href="<?php echo e(route('login')); ?>">Login</a><a class="btn btn-primary landing-nav-cta" href="<?php echo e(route('register')); ?>">Register <i class="bi bi-arrow-up-right"></i></a></nav></div></header>
<main><?php echo $__env->yieldContent('content'); ?></main>

</body>
</html>
<?php /**PATH C:\laragon\www\campus-cleaning\resources\views/layouts/public.blade.php ENDPATH**/ ?>