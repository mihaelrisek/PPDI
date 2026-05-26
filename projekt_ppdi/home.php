<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

requireLogin();

$currentUser = getCurrentUser();
$query = trim((string) ($_GET['search'] ?? ''));
$cars = [];
$error = '';

try {
    $cars = filterCars(loadCars(), $query);
} catch (Throwable $exception) {
    $error = 'Podaci o automobilima trenutno nisu dostupni.';
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog sportskih automobila</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-page">
    <div class="page-gradient"></div>
    <header class="site-header">
        <div>
            <p class="eyebrow">Dobrodošli, <?= escape($currentUser['username'] ?? 'korisnik') ?></p>
            <h1>Katalog sportskih automobila</h1>
        </div>
        <nav class="top-nav">
            <span class="nav-role"><?= escape($currentUser['role'] ?? 'guest') ?></span>
            <a class="nav-link" href="logout.php">Odjava</a>
        </nav>
    </header>

    <main class="container">
        <section class="search-panel">
            <form method="get" class="search-form">
                <label for="search" class="search-label">Pretraži po modelu, marki, godini, motoru, snazi ili opisu</label>
                <div class="search-row">
                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Primjer: Ferrari, 2020, AWD, 720 KS..."
                        value="<?= escape($query) ?>"
                    >
                    <button type="submit">Pretraži</button>
                </div>
            </form>
        </section>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= escape($error) ?></div>
        <?php elseif ($cars === []): ?>
            <div class="empty-state">Nema pronađenih rezultata.</div>
        <?php else: ?>
            <section class="cars-grid">
                <?php foreach ($cars as $car): ?>
                    <a class="car-card" href="details.php?id=<?= (int) $car['id'] ?>">
                        <div class="card-image-wrap">
                            <img src="<?= escape($car['image']) ?>" alt="<?= escape($car['brand'] . ' ' . $car['model']) ?>">
                        </div>
                        <div class="card-body">
                            <div class="card-heading">
                                <p class="card-brand"><?= escape($car['brand']) ?></p>
                                <h2><?= escape($car['model']) ?></h2>
                            </div>
                            <div class="spec-grid">
                                <span><strong>Godina:</strong> <?= escape($car['year']) ?></span>
                                <span><strong>Snaga:</strong> <?= escape($car['horsepower']) ?></span>
                                <span><strong>0-100:</strong> <?= escape($car['acceleration']) ?></span>
                                <span><strong>Maks.:</strong> <?= escape($car['topSpeed']) ?></span>
                            </div>
                            <p class="card-description"><?= escape($car['shortDescription']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
