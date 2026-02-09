<?php
require_once __DIR__ . '/../includes/auth_helper.php';
require_once __DIR__ . '/../includes/header.php';
Auth::requireRole('team_member');

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM tasks WHERE assigned_to = ? ORDER BY due_date ASC');
$stmt->execute([$userId]);
$tasks = $stmt->fetchAll();
?>
<section class="dashboard">
    <h1>My Dashboard</h1>
    <div class="card">
        <h2>My Tasks</h2>
        <ul>
            <?php foreach ($tasks as $task) : ?>
                <li>
                    <strong><?php echo sanitize_output($task['title']); ?></strong>
                    <span class="text-muted">(<?php echo sanitize_output($task['status']); ?>)</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
