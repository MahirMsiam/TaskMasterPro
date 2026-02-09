<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/functions.php';
start_secure_session();
$csrfToken = ensure_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize_output(APP_NAME); ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/forms.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
<?php require_once __DIR__ . '/navigation.php'; ?>
<main class="app-main">
