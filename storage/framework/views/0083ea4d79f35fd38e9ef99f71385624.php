<?php $__env->startSection('title', 'Overview'); ?>
<?php $__env->startSection('content'); ?>
<div class="welcome-row"><div><div class="eyebrow"><span class="live-dot"></span> FACILITIES OVERVIEW</div><h1>Good <?php echo e(now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening')); ?>, team <span class="wave">✦</span></h1><p class="subtitle">Here’s what’s happening around campus today.</p></div><a href="<?php echo e(route('records.create')); ?>" class="btn btn-primary btn-new"><i class="bi bi-plus-lg"></i> Create task</a></div>
<div class="stats-grid">
    <div class="stat-card stat-total"><div class="stat-head"><span>Total tasks</span><span class="stat-icon"><i class="bi bi-clipboard2-check"></i></span></div><div class="stat-number"><?php echo e($total); ?></div><div class="stat-foot">All cleaning records</div><div class="stat-decoration"></div></div>
    <div class="stat-card"><div class="stat-head"><span>Pending</span><span class="stat-icon icon-amber"><i class="bi bi-hourglass-split"></i></span></div><div class="stat-number"><?php echo e($pending); ?></div><div class="stat-foot"><span class="tiny-dot amber"></span> Awaiting attention</div></div>
    <div class="stat-card"><div class="stat-head"><span>In progress</span><span class="stat-icon icon-blue"><i class="bi bi-arrow-repeat"></i></span></div><div class="stat-number"><?php echo e($inProgress); ?></div><div class="stat-foot"><span class="tiny-dot blue"></span> Being taken care of</div></div>
    <div class="stat-card"><div class="stat-head"><span>Completed</span><span class="stat-icon icon-green"><i class="bi bi-check2-circle"></i></span></div><div class="stat-number"><?php echo e($completed); ?></div><div class="stat-foot"><span class="tiny-dot green"></span> Campus-ready spaces</div></div>
</div>
<div class="section-heading"><div><h2>Upcoming schedule</h2><p>Your next campus cleaning tasks</p></div><a href="<?php echo e(route('records.index')); ?>" class="text-link">View all tasks <i class="bi bi-arrow-right"></i></a></div>
<div class="panel schedule-panel">
    <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <a class="schedule-row" href="<?php echo e(route('records.show', $record)); ?>"><div class="date-block"><strong><?php echo e($record->scheduled_date->format('d')); ?></strong><small><?php echo e($record->scheduled_date->format('M')); ?></small></div><div class="schedule-info"><strong><?php echo e($record->location); ?></strong><span><?php echo e($record->category); ?> <span class="dot-sep">·</span> <?php echo e($record->assigned_staff); ?></span></div><span class="badge-status status-<?php echo e(Illuminate\Support\Str::slug($record->status)); ?>"><?php echo e($record->status); ?></span><i class="bi bi-chevron-right row-chevron"></i></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state"><span class="empty-icon"><i class="bi bi-calendar2-check"></i></span><strong>No upcoming tasks yet</strong><p>New records will show up here when scheduled.</p><a href="<?php echo e(route('records.create')); ?>" class="text-link">Schedule a cleaning <i class="bi bi-arrow-right"></i></a></div>
    <?php endif; ?>
</div>
<div class="bottom-note"><i class="bi bi-shield-check"></i><span>Every clean space helps make room for great ideas.</span><span class="ms-auto">Updated <?php echo e(now()->format('g:i A')); ?></span></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\campus-cleaning\resources\views\dashboard.blade.php ENDPATH**/ ?>