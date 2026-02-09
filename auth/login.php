<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../includes/functions.php';

start_secure_session();
$csrfToken = ensure_csrf_token();
$errors = [];
$info = null;

if (isset($_GET['registered'])) {
    $info = 'Registration successful. Please log in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token($_POST['csrf_token'] ?? null);

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $errors[] = 'Invalid email or password.';
    } else {
        $lockedUntil = null;
        if ($user['failed_login_attempts'] >= MAX_LOGIN_ATTEMPTS && $user['last_failed_login']) {
            $lockedUntil = strtotime($user['last_failed_login'] . ' +' . LOGIN_LOCKOUT_MINUTES . ' minutes');
        }

        if ($lockedUntil && $lockedUntil > time()) {
            $errors[] = 'Account locked. Please try again later.';
        } elseif (!password_verify($password, $user['password_hash'])) {
            $update = $pdo->prepare('UPDATE users SET failed_login_attempts = failed_login_attempts + 1, last_failed_login = NOW() WHERE user_id = ?');
            $update->execute([$user['user_id']]);
            $errors[] = 'Invalid email or password.';
        } else {
            $reset = $pdo->prepare('UPDATE users SET failed_login_attempts = 0, last_failed_login = NULL, last_login = NOW() WHERE user_id = ?');
            $reset->execute([$user['user_id']]);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['organization_id'] = $user['organization_id'];
            $_SESSION['last_activity'] = time();

            switch ($user['role']) {
                case 'super_admin':
                    header('Location: /dashboard/admin.php');
                    break;
                case 'project_manager':
                    header('Location: /dashboard/manager.php');
                    break;
                case 'client_observer':
                    header('Location: /dashboard/client.php');
                    break;
                default:
                    header('Location: /dashboard/user.php');
                    break;
            }
            exit;
        }
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<section class="auth-card">
    <h1>Welcome back</h1>
    <p class="auth-card__subtitle">Log in to manage your projects.</p>

    <?php if ($info) : ?>
        <div class="alert alert--success">
            <?php echo sanitize_output($info); ?>
        </div>
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

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button class="button button--primary" type="submit">Login</button>
        <div class="form__footer">
            <a href="/auth/forgot_password.php">Forgot password?</a>
            <span> | </span>
            <a href="/auth/register.php">Create account</a>
        </div>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
