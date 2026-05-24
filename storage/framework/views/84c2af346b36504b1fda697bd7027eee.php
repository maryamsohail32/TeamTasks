
<?php $__env->startSection('content'); ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px;">
    <div>
        <h1 style="font-size:26px; font-weight:600; color:#1E1B4B; letter-spacing:-0.5px;">My Teams</h1>
        <p style="font-size:13px; color:#9CA3AF; margin-top:3px;">Manage and collaborate with your teams</p>
    </div>
    <a href="<?php echo e(route('teams.create')); ?>" class="btn btn-primary">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Team
    </a>
</div>

<?php if($teams->isEmpty()): ?>
<div style="text-align:center; padding:80px 0;">
    <div style="width:60px;height:60px;border-radius:16px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#6366F1" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <h3 style="font-size:16px; font-weight:600; color:#1E1B4B; margin-bottom:6px;">No teams yet</h3>
    <p style="font-size:13px; color:#9CA3AF; margin-bottom:20px;">Create your first team to start collaborating</p>
    <a href="<?php echo e(route('teams.create')); ?>" class="btn btn-primary">Create a team</a>
</div>
<?php else: ?>
<div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:16px;">
    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('teams.show',$team)); ?>" style="text-decoration:none;">
        <div class="card" style="cursor:pointer; transition:all 0.15s;"
             onmouseover="this.style.borderColor='#6366F1'; this.style.transform='translateY(-2px)'"
             onmouseout="this.style.borderColor='#EDE9FE'; this.style.transform='none'">
            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#818CF8,#4F46E5);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;color:#fff;margin-bottom:14px;">
                <?php echo e(strtoupper(substr($team->name,0,1))); ?>

            </div>
            <h3 style="font-size:15px; font-weight:600; color:#1E1B4B; margin-bottom:4px;"><?php echo e($team->name); ?></h3>
            <p style="font-size:12px; color:#9CA3AF;"><?php echo e($team->members->count()); ?> member<?php echo e($team->members->count()!==1?'s':''); ?></p>
            <div style="margin-top:14px; padding-top:14px; border-top:1px solid #F3F4F6; display:flex; justify-content:space-between; font-size:11px; color:#9CA3AF;">
                <span><?php echo e($team->tasks->count()); ?> tasks</span>
                <span style="color:#6366F1; font-weight:500;">View →</span>
            </div>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Maryam Sohail\Downloads\teamtasks_2\teamtasks\resources\views/teams/index.blade.php ENDPATH**/ ?>