<?php if($errors->any()): ?><div class="alert alert-danger"><strong>Please check the task details:</strong><ul class="mb-0 mt-2"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
<form method="POST" action="<?php echo e($action); ?>"><?php echo csrf_field(); ?> <?php if($method !== 'POST'): ?><?php echo method_field($method); ?><?php endif; ?>
<div class="form-section"><div class="form-section-heading"><span class="form-section-icon"><i class="bi bi-geo-alt"></i></span><div><strong>Cleaning task</strong><small>Choose the location and describe the work.</small></div></div><div class="row g-3">
<div class="col-md-6"><label class="form-label" for="location_id">Campus location <span class="required">*</span></label><select id="location_id" name="location_id" class="form-select" required><option value="">Choose location</option><?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($location->id); ?>" <?php if((string)old('location_id',$record->location_id ?? '')===(string)$location->id): echo 'selected'; endif; ?>><?php echo e($location->name); ?><?php echo e($location->building ? ' · '.$location->building : ''); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['location_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-6"><label class="form-label" for="category_id">Cleaning category <span class="required">*</span></label><select id="category_id" name="category_id" class="form-select" required><option value="">Choose category</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if((string)old('category_id',$record->category_id ?? '')===(string)$category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-12"><label class="form-label" for="description">Description <span class="required">*</span></label><textarea id="description" name="description" class="form-control" rows="4" maxlength="2000" placeholder="Describe the cleaning needed..." required><?php echo e(old('description',$record->description ?? '')); ?></textarea><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div></div></div>
<div class="form-section"><div class="form-section-heading"><span class="form-section-icon icon-purple"><i class="bi bi-people"></i></span><div><strong>Assignment & schedule</strong><small>Set responsibility and timing.</small></div></div><div class="row g-3">
<div class="col-md-6"><label class="form-label" for="assigned_staff_id">Assigned staff member</label><select id="assigned_staff_id" name="assigned_staff_id" class="form-select"><option value="">Unassigned</option><?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($member->id); ?>" <?php if((string)old('assigned_staff_id',$record->assigned_staff_id ?? '')===(string)$member->id): echo 'selected'; endif; ?>><?php echo e($member->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['assigned_staff_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-6"><label class="form-label" for="scheduled_date">Scheduled date <span class="required">*</span></label><input id="scheduled_date" name="scheduled_date" type="date" class="form-control" value="<?php echo e(old('scheduled_date',isset($record)?$record->scheduled_date->format('Y-m-d'):'')); ?>" required><?php $__errorArgs = ['scheduled_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-6"><label class="form-label" for="priority">Priority <span class="required">*</span></label><select id="priority" name="priority" class="form-select" required><?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($option->name); ?>" <?php if(old('priority',$record->priority ?? 'Medium')===$option->name): echo 'selected'; endif; ?>><?php echo e($option->name); ?> — <?php echo e($option->description); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<div class="col-md-6"><label class="form-label" for="status">Task status <span class="required">*</span></label><select id="status" name="status" class="form-select" required><?php $__currentLoopData = ['Pending','In Progress','Completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(old('status',$record->status ?? 'Pending')===$option): echo 'selected'; endif; ?>><?php echo e($option); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<?php if(isset($record)): ?><div class="col-12"><label class="form-label" for="completion_remarks">Completion remarks</label><textarea id="completion_remarks" name="completion_remarks" class="form-control" rows="2" maxlength="2000"><?php echo e(old('completion_remarks',$record->completion_remarks)); ?></textarea></div><?php endif; ?>
</div></div>
<div class="form-actions"><a href="<?php echo e(isset($record)?route('admin.records.show',$record):route('admin.records.index')); ?>" class="btn btn-light">Cancel</a><button class="btn btn-primary btn-new" type="submit"><i class="bi bi-check2"></i> <?php echo e($submitLabel); ?></button></div></form>
<?php /**PATH C:\laragon\www\campus-cleaning\resources\views\records\_form.blade.php ENDPATH**/ ?>