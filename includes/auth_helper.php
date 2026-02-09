<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/functions.php';

class Auth
{
    public static function checkLogin(): void
    {
        enforce_session_timeout();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login.php');
            exit;
        }
    }

    public static function requireRole(string $required_role): void
    {
        self::checkLogin();
        if (($_SESSION['role'] ?? '') !== $required_role) {
            http_response_code(403);
            header('Location: /errors/403.php');
            exit;
        }
    }

    public static function hasPermission(string $permission): bool
    {
        $role = $_SESSION['role'] ?? '';
        $permissions = [
            'super_admin' => ['manage_users', 'manage_projects', 'view_analytics'],
            'project_manager' => ['manage_projects', 'manage_tasks', 'view_analytics'],
            'team_member' => ['manage_tasks'],
            'client_observer' => ['view_projects'],
        ];

        return in_array($permission, $permissions[$role] ?? [], true);
    }

    public static function getCurrentUser(): ?array
    {
        global $pdo;
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}
?>
