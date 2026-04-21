<?php
$title = $title ?? 'ShopSmart';
$flashMessages = flash_messages();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
</head>
<body class="ss-body">
<div class="ss-orb ss-orb-left"></div>
<div class="ss-orb ss-orb-right"></div>

<div class="container-xl py-4 ss-page-wrap">
    <div class="ss-frame">
        <div class="ss-titlebar">
            <span><?= e($title) ?></span>
            <span class="ss-title-icons">&#9472; &#9633; &#11036;</span>
        </div>

        <?php require base_path('app/Views/partials/nav.php'); ?>

        <main class="ss-main">
            <?php foreach ($flashMessages as $flashMessage): ?>
                <?php
                $type = $flashMessage['type'] ?? 'info';
                $alertClass = match ($type) {
                    'success' => 'alert-success',
                    'warning' => 'alert-warning',
                    'danger' => 'alert-danger',
                    default => 'alert-info',
                };
                ?>
                <div class="alert <?= e($alertClass) ?> alert-dismissible fade show" role="alert">
                    <?= e($flashMessage['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endforeach; ?>

            <?= $content ?>
        </main>

        <footer class="ss-footer">ShopSmart Demo Academica 2026</footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset_url('js/app.js')) ?>"></script>
</body>
</html>
