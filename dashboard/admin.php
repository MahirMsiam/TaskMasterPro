<?php
require_once __DIR__ . '/../includes/auth_helper.php';
require_once __DIR__ . '/../includes/header.php';
Auth::requireRole('super_admin');

$projectStats = $pdo->query('SELECT status, COUNT(*) as total FROM projects GROUP BY status')->fetchAll();
$userStats = $pdo->query('SELECT role, COUNT(*) as total FROM users GROUP BY role')->fetchAll();
$activity = $pdo->query('SELECT action, entity_type, description, created_at FROM activity_log ORDER BY created_at DESC LIMIT 8')->fetchAll();
?>
<section class="dashboard">
    <h1>Super Admin Dashboard</h1>
    <div class="card-grid">
        <div class="card">
            <h2>Projects</h2>
            <ul>
                <?php foreach ($projectStats as $stat) : ?>
                    <li><?php echo sanitize_output($stat['status']); ?>: <?php echo sanitize_output($stat['total']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card">
            <h2>Users</h2>
            <ul>
                <?php foreach ($userStats as $stat) : ?>
                    <li><?php echo sanitize_output($stat['role']); ?>: <?php echo sanitize_output($stat['total']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card">
            <h2>Quick Actions</h2>
            <div class="button-group">
                <a class="button button--primary" href="/users/create.php">Add User</a>
                <a class="button button--secondary" href="/projects/create.php">Create Project</a>
                <a class="button button--outline" href="/analytics/overview.php">View Analytics</a>
            </div>
        </div>
    </div>
    <div class="card">
        <h2>Recent Activity</h2>
        <ul>
            <?php foreach ($activity as $item) : ?>
                <li>
                    <strong><?php echo sanitize_output($item['action']); ?></strong>
                    <?php echo sanitize_output($item['entity_type']); ?>
                    - <?php echo sanitize_output($item['description']); ?>
                    <span class="text-muted"><?php echo sanitize_output($item['created_at']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
