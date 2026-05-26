<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!empty($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

header('Location: login.php');
exit;
