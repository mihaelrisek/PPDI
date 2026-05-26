<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

requireLogin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$car = null;
$error = '';

if ($id === false || $id === null) {
    $error = 'Neispravan ID automobila.';
} else {
    try {
        $car = findCarById($id);
        if ($car === null) {
            $error = 'Automobil nije pronađen.';
        }
    } catch (Throwable $exception) {
        $error = 'Podaci o automobilu trenutno nisu dostupni.';
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalji automobila</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-page">
    <div class="page-gradient"></div>
    <main class="container details-container">
        <a class="back-link" href="home.php">← Povratak na katalog</a>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= escape($error) ?></div>
        <?php elseif ($car !== null): ?>
            <article class="details-card">
                <div class="details-hero">
                    <img src="<?= escape($car['image']) ?>" alt="<?= escape($car['brand'] . ' ' . $car['model']) ?>">
                    <div class="details-overlay">
                        <p class="eyebrow"><?= escape($car['brand']) ?></p>
                        <h1><?= escape($car['model']) ?></h1>
                        <p><?= escape($car['shortDescription']) ?></p>
                    </div>
                </div>

                <section class="details-content">
                    <div class="details-specs">
                        <div class="info-card"><span>Godina</span><strong><?= escape($car['year']) ?></strong></div>
                        <div class="info-card"><span>Motor</span><strong><?= escape($car['engine']) ?></strong></div>
                        <div class="info-card"><span>Snaga</span><strong><?= escape($car['horsepower']) ?></strong></div>
                        <div class="info-card"><span>0-100 km/h</span><strong><?= escape($car['acceleration']) ?></strong></div>
                        <div class="info-card"><span>Maks. brzina</span><strong><?= escape($car['topSpeed']) ?></strong></div>
                        <div class="info-card"><span>Pogon</span><strong><?= escape($car['drivetrain']) ?></strong></div>
                        <div class="info-card"><span>Mjenjač</span><strong><?= escape($car['transmission']) ?></strong></div>
                        <div class="info-card"><span>Cijena</span><strong><?= escape($car['price']) ?></strong></div>
                    </div>

                    <section class="details-description">
                        <h2>Detaljan opis</h2>
                        <p><?= escape($car['fullDescription']) ?></p>
                    </section>
                </section>
            </article>
        <?php endif; ?>
    </main>
</body>
</html>
