<?php
require_once __DIR__ . '/../includes/auth_helper.php';
require_once __DIR__ . '/../includes/header.php';
Auth::requireRole('project_manager');

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT projects.*, COUNT(project_team.user_id) as team_size
    FROM projects
    LEFT JOIN project_team ON project_team.project_id = projects.project_id
    WHERE projects.created_by = ?
    GROUP BY projects.project_id
    ORDER BY projects.updated_at DESC');
$stmt->execute([$userId]);
$projects = $stmt->fetchAll();
?>
<section class="dashboard">
    <h1>Project Manager Dashboard</h1>
    <div class="card-grid">
        <?php foreach ($projects as $project) : ?>
            <div class="card">
                <h2><?php echo sanitize_output($project['project_name']); ?></h2>
                <p><?php echo sanitize_output($project['description']); ?></p>
                <p>Status: <?php echo sanitize_output($project['status']); ?></p>
                <p>Progress: <?php echo sanitize_output($project['completion_percentage']); ?>%</p>
                <p>Team size: <?php echo sanitize_output($project['team_size']); ?></p>
                <a class="button button--primary" href="/projects/view.php?id=<?php echo sanitize_output($project['project_id']); ?>">View Project</a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <h2>Quick Actions</h2>
        <div class="button-group">
            <a class="button button--primary" href="/tasks/create.php">Create Task</a>
            <a class="button button--secondary" href="/analytics/team.php">View Team Workload</a>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
