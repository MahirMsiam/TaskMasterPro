<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../includes/functions.php';

start_secure_session();
$csrfToken = ensure_csrf_token();
$errors = [];
$success = null;
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token($_POST['csrf_token'] ?? null);
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($password) < PASSWORD_MIN_LENGTH
        || !preg_match('/[A-Z]/', $password)
        || !preg_match('/\d/', $password)
        || !preg_match('/[^A-Za-z0-9]/', $password)
    ) {
        $errors[] = 'Password must be at least 8 characters and include 1 uppercase letter, 1 number, and 1 special character.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()');
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $errors[] = 'Reset link is invalid or expired.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $update = $pdo->prepare('UPDATE users SET password_hash = ? WHERE email = ?');
            $update->execute([$passwordHash, $reset['email']]);

            $delete = $pdo->prepare('DELETE FROM password_resets WHERE token = ?');
            $delete->execute([$token]);

            $success = 'Password updated. You can now log in.';
        }
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<section class="auth-card">
    <h1>Create a new password</h1>
    <p class="auth-card__subtitle">Enter a strong password for your account.</p>

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
        <input type="hidden" name="token" value="<?php echo sanitize_output($token); ?>">

        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button class="button button--primary" type="submit">Update password</button>
        <p class="form__footer"><a href="/auth/login.php">Back to login</a></p>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
