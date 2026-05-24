<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Team Invitation</title>
</head>
<body>
    <h2>Hello!</h2>
    <p>You’ve been invited to join the team <strong><?php echo e($team->name); ?></strong>.</p>

    <p>Click below to register and join:</p>
    <a href="<?php echo e(url('/register?email=' . $user->email)); ?>">
        Join Team
    </a>

    <p>Thanks,<br>TeamTasks</p>
</body>
</html>
<?php /**PATH C:\Users\Maryam Sohail\Downloads\teamtasks_2\teamtasks\resources\views/emails/team_invite.blade.php ENDPATH**/ ?>