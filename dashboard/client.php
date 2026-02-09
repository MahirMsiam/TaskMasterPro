<?php
require_once __DIR__ . '/../includes/auth_helper.php';
require_once __DIR__ . '/../includes/header.php';
Auth::requireRole('client_observer');
?>
<section class="dashboard">
    <h1>Client Dashboard</h1>
    <div class="card">
        <p>Welcome! Your visible projects will appear here.</p>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
