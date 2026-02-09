<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

start_secure_session();
$token = $_GET['token'] ?? '';
$message = 'Verification token is invalid.';

if ($token) {
    $stmt = $pdo->prepare('SELECT user_id FROM users WHERE verification_token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare('UPDATE users SET email_verified = 1, verification_token = NULL WHERE user_id = ?');
        $update->execute([$user['user_id']]);
        $message = 'Email verified successfully. You can log in.';
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<section class="auth-card">
    <h1>Email verification</h1>
    <p><?php echo sanitize_output($message); ?></p>
    <p class="form__footer"><a href="/auth/login.php">Go to login</a></p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
