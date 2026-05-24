
<?php $__env->startSection('content'); ?>

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
    <div>
        <p style="font-size:12px; color:#9CA3AF; margin-bottom:5px;">
            <a href="<?php echo e(route('teams.index')); ?>" style="color:#6366F1; text-decoration:none;">Teams</a>
            <span style="margin:0 6px;">›</span>
        </p>
        <h1 style="font-size:26px; font-weight:600; color:#1E1B4B; letter-spacing:-0.5px;"><?php echo e($team->name); ?></h1>
        <p style="font-size:13px; color:#9CA3AF; margin-top:3px;"><?php echo e($team->members->count()); ?> members · <?php echo e($tasks->count()); ?> tasks</p>
    </div>
    <a href="<?php echo e(route('tasks.create', $team)); ?>" class="btn btn-primary">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Task
    </a>
</div>


<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:32px;">
    <?php
    $cols = [
        'todo'        => ['label'=>'To Do',       'color'=>'#6366F1','bg'=>'#EEF2FF','dot'=>'#818CF8'],
        'in_progress' => ['label'=>'In Progress',  'color'=>'#F59E0B','bg'=>'#FFFBEB','dot'=>'#FCD34D'],
        'done'        => ['label'=>'Done',          'color'=>'#10B981','bg'=>'#ECFDF5','dot'=>'#34D399'],
    ];
    ?>

    <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="background:#F8F7FF; border-radius:16px; padding:18px; border:1px solid #EDE9FE;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="width:8px;height:8px;border-radius:50%;background:<?php echo e($col['dot']); ?>;display:inline-block;"></span>
                <span style="font-size:12px; font-weight:600; color:#374151; letter-spacing:0.04em; text-transform:uppercase;"><?php echo e($col['label']); ?></span>
            </div>
            <span style="background:<?php echo e($col['bg']); ?>; color:<?php echo e($col['color']); ?>; font-size:11px; font-weight:600; padding:2px 9px; border-radius:99px;">
                <?php echo e($tasks->where('status',$status)->count()); ?>

            </span>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px;">
        <?php $__empty_1 = true; $__currentLoopData = $tasks->where('status',$status); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="background:#fff; border-radius:12px; padding:14px; border:1px solid #EDE9FE; transition:box-shadow 0.15s;"
             onmouseover="this.style.boxShadow='0 4px 16px rgba(99,102,241,0.1)'"
             onmouseout="this.style.boxShadow='none'">

            <?php
            $pColors = ['high'=>['#FEF2F2','#DC2626'],'medium'=>['#FFFBEB','#D97706'],'low'=>['#F0FDF4','#16A34A']];
            $pc = $pColors[$task->priority] ?? ['#F3F4F6','#6B7280'];
            ?>

            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px; margin-bottom:10px;">
                <p style="font-size:13px; font-weight:500; color:#111827; line-height:1.45; flex:1;"><?php echo e($task->title); ?></p>
                <span style="background:<?php echo e($pc[0]); ?>; color:<?php echo e($pc[1]); ?>; font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; flex-shrink:0; text-transform:uppercase; letter-spacing:0.04em;">
                    <?php echo e($task->priority); ?>

                </span>
            </div>

            <?php if($task->assignee): ?>
            <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                <div style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#818CF8,#6366F1);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;color:#fff;">
                    <?php echo e(strtoupper(substr($task->assignee->name,0,2))); ?>

                </div>
                <span style="font-size:11px; color:#6B7280;"><?php echo e($task->assignee->name); ?></span>
            </div>
            <?php endif; ?>

            <?php if($task->due_date): ?>
            <div style="display:inline-flex; align-items:center; gap:4px; background:<?php echo e($task->isOverdue() ? '#FEF2F2' : '#F3F4F6'); ?>; color:<?php echo e($task->isOverdue() ? '#DC2626' : '#6B7280'); ?>; padding:3px 8px; border-radius:6px; font-size:11px; margin-bottom:8px;">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <?php echo e($task->due_date->format('M d, Y')); ?><?php echo e($task->isOverdue() ? ' · Overdue' : ''); ?>

            </div>
            <?php endif; ?>

            <div style="display:flex; gap:8px; padding-top:8px; border-top:1px solid #F3F4F6;">
                <a href="<?php echo e(route('tasks.edit',[$team,$task])); ?>"
                   style="font-size:11px; font-weight:500; color:#6366F1; text-decoration:none; padding:4px 10px; background:#EEF2FF; border-radius:6px;">
                   Edit
                </a>
                <form method="POST" action="<?php echo e(route('tasks.destroy',[$team,$task])); ?>" onsubmit="return confirm('Delete this task?')" style="display:inline;">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button style="font-size:11px; font-weight:500; color:#DC2626; background:#FEF2F2; border:none; padding:4px 10px; border-radius:6px; cursor:pointer; font-family:inherit;">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="text-align:center; padding:36px 16px; color:#D1D5DB;">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px; display:block; color:#E5E7EB;"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 12h6M12 9v6"/></svg>
            <p style="font-size:12px;">No tasks here</p>
        </div>
        <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $team)): ?>
<div class="card" style="max-width:460px;">
    <h3 style="font-size:15px; font-weight:600; color:#1E1B4B; margin-bottom:4px;">Team Members</h3>
    <p style="font-size:12px; color:#9CA3AF; margin-bottom:16px;">Invite people to collaborate</p>

    <form method="POST" action="<?php echo e(route('teams.invite',$team)); ?>" style="display:flex; gap:8px; margin-bottom:16px;">
        <?php echo csrf_field(); ?>
        <input name="email" type="email" required placeholder="colleague@email.com"
            style="flex:1; border:1px solid #E5E7EB; border-radius:9px; padding:9px 14px; font-size:13px; outline:none; font-family:inherit; color:#111827;"
            onfocus="this.style.borderColor='#6366F1'" onblur="this.style.borderColor='#E5E7EB'">
        <button type="submit" class="btn btn-primary" style="padding:9px 18px;">Invite</button>
    </form>
    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p style="color:#DC2626; font-size:12px; margin-top:-10px; margin-bottom:12px;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <div style="border-top:1px solid #F3F4F6; padding-top:14px; display:flex; flex-direction:column; gap:10px;">
        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#818CF8,#6366F1);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#fff;flex-shrink:0;">
                <?php echo e(strtoupper(substr($member->name,0,2))); ?>

            </div>
            <div style="flex:1;">
                <p style="font-size:13px; font-weight:500; color:#111827;"><?php echo e($member->name); ?></p>
                <p style="font-size:11px; color:#9CA3AF;"><?php echo e($member->email); ?></p>
            </div>
            <span style="font-size:11px; font-weight:500; padding:3px 10px; border-radius:99px;
                <?php echo e($member->pivot->role === 'owner' ? 'background:#EEF2FF;color:#4F46E5;' : 'background:#F3F4F6;color:#6B7280;'); ?>">
                <?php echo e($member->pivot->role); ?>

            </span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Maryam Sohail\Downloads\teamtasks_2\teamtasks\resources\views/teams/show.blade.php ENDPATH**/ ?>