<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

start_secure_session();
$csrfToken = ensure_csrf_token();
$errors = [];
$success = null;

function send_verification_email(string $email, string $token): void
{
    // Placeholder for email sending integration.
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token($_POST['csrf_token'] ?? null);

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $organizationName = trim($_POST['organization_name'] ?? '');

    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

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

    if ($organizationName === '') {
        $errors[] = 'Organization name is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with this email already exists.';
        }
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $verificationToken = bin2hex(random_bytes(32));

        try {
            $pdo->beginTransaction();

            $orgStmt = $pdo->prepare('INSERT INTO organizations (org_name) VALUES (?)');
            $orgStmt->execute([$organizationName]);
            $organizationId = (int) $pdo->lastInsertId();

            $userStmt = $pdo->prepare('INSERT INTO users (email, password_hash, full_name, role, organization_id, email_verified, verification_token)
                VALUES (?, ?, ?, ?, ?, 0, ?)');
            $userStmt->execute([
                $email,
                $passwordHash,
                $fullName,
                'super_admin',
                $organizationId,
                $verificationToken,
            ]);

            $pdo->commit();
            send_verification_email($email, $verificationToken);
            header('Location: /auth/login.php?registered=1');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<section class="auth-card">
    <h1>Create your account</h1>
    <p class="auth-card__subtitle">Start managing projects with AI-powered insights.</p>

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
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="organization_name">Organization Name</label>
        <input type="text" id="organization_name" name="organization_name" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <small class="form__hint">Minimum 8 characters, one uppercase, one number, one special character.</small>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button class="button button--primary" type="submit">Create Account</button>
        <p class="form__footer">Already have an account? <a href="/auth/login.php">Login</a></p>
    </form>
</section>
<script src="/assets/js/form-validation.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
