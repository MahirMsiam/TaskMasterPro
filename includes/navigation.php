<?php
$role = $_SESSION['role'] ?? null;
?>
<nav class="app-nav">
    <div class="app-nav__brand">
        <a href="/">TaskMaster Pro</a>
    </div>
    <ul class="app-nav__links">
        <?php if ($role === 'super_admin') : ?>
            <li><a href="/dashboard/admin.php">Dashboard</a></li>
            <li><a href="/projects/index.php">Projects</a></li>
            <li><a href="/users/manage.php">Users</a></li>
            <li><a href="/analytics/overview.php">Analytics</a></li>
            <li><a href="/profile/view.php">Settings</a></li>
        <?php elseif ($role === 'project_manager') : ?>
            <li><a href="/dashboard/manager.php">Dashboard</a></li>
            <li><a href="/projects/index.php">Projects</a></li>
            <li><a href="/tasks/my_tasks.php">Tasks</a></li>
            <li><a href="/analytics/team.php">Team</a></li>
            <li><a href="/analytics/overview.php">Analytics</a></li>
        <?php elseif ($role === 'team_member') : ?>
            <li><a href="/dashboard/user.php">Dashboard</a></li>
            <li><a href="/tasks/my_tasks.php">My Tasks</a></li>
            <li><a href="/projects/index.php">Projects</a></li>
            <li><a href="/profile/view.php">Profile</a></li>
        <?php elseif ($role === 'client_observer') : ?>
            <li><a href="/dashboard/client.php">Dashboard</a></li>
            <li><a href="/projects/index.php">Projects</a></li>
            <li><a href="/profile/view.php">Profile</a></li>
        <?php endif; ?>
    </ul>
    <div class="app-nav__actions">
        <?php if ($role) : ?>
            <a class="button button--outline" href="/auth/logout.php">Logout</a>
        <?php else : ?>
            <a class="button button--primary" href="/auth/login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>
