<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../includes/functions.php';

start_secure_session();
$csrfToken = ensure_csrf_token();
$errors = [];
$success = null;

function send_reset_email(string $email, string $token): void
{
    // Placeholder for reset email sending.
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token($_POST['csrf_token'] ?? null);
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email.';
    }

    if (empty($errors)) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$email, $token, $expiresAt]);

        send_reset_email($email, $token);
        $success = 'If an account exists, a reset link has been sent.';
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<section class="auth-card">
    <h1>Reset your password</h1>
    <p class="auth-card__subtitle">We'll send a reset link to your email.</p>

    <?php if ($success) : ?>
        <div class="alert alert--success"><?php echo sanitize_output($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <div class="alert alert--danger">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo sanitize_output($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="form" method="post" action="">
        <input type="hidden" name="csrf_token" value="<?php echo sanitize_output($csrfToken); ?>">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <button class="button button--primary" type="submit">Send reset link</button>
        <p class="form__footer"><a href="/auth/login.php">Back to login</a></p>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
