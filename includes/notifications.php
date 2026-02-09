<?php
require_once __DIR__ . '/../config/database.php';

class Notifications
{
    public static function send(int $user_id, array $data): void
    {
        global $pdo;
        $sql = 'INSERT INTO notifications (user_id, type, title, message, link, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $user_id,
            $data['type'],
            $data['title'],
            $data['message'],
            $data['link'],
        ]);
    }

    public static function getUnread(int $user_id): array
    {
        global $pdo;
        $sql = 'SELECT * FROM notifications WHERE user_id = ? AND is_read = 0
                ORDER BY created_at DESC LIMIT 20';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}
?>
