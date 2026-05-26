<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!empty($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Unesite korisničko ime i lozinku.';
    } else {
        try {
            $user = findUser($username, $password);

            if ($user !== null) {
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'role' => $user['role'],
                ];

                header('Location: home.php');
                exit;
            }

            $error = 'Neispravno korisničko ime ili lozinka.';
        } catch (Throwable $exception) {
            $error = 'Dogodila se greška pri učitavanju korisnika.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Cars Catalog | Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-card">
            <p class="eyebrow">XML Powered Garage</p>
            <h1>Sport Cars Catalog</h1>
            <p class="login-intro">Prijavite se za pregled kataloga sportskih automobila i detaljnih specifikacija modela.</p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?= escape($error) ?></div>
            <?php endif; ?>

            <form method="post" class="login-form" novalidate>
                <label for="username">Korisničko ime</label>
                <input type="text" id="username" name="username" placeholder="npr. admin" value="<?= escape($_POST['username'] ?? '') ?>" required>

                <label for="password">Lozinka</label>
                <input type="password" id="password" name="password" placeholder="Unesite lozinku" required>

                <button type="submit">Prijava</button>
            </form>

            <div class="login-demo">
                <span>Demo korisnici:</span>
                <strong>admin / admin123</strong>,
                <strong>editor / sports2026</strong>,
                <strong>guest / katalog</strong>
            </div>
        </section>
    </main>
</body>
</html>
