<?php
$user = auth_user();
$isAdmin = $user !== null && (($user['rol_nombre'] ?? '') === 'admin');

$subTitle = 'Tienda online con gestion de usuarios y productos';
if (nav_active('/login', true)) {
    $subTitle = 'Registro e inicio de sesion';
} elseif (nav_active('/carrito')) {
    $subTitle = 'Revisa tus productos antes de confirmar';
} elseif (nav_active('/admin')) {
    $subTitle = 'Gestion de catalogo';
}
?>

<div class="ss-topline">Cuenta ShopSmart | <?= e($subTitle) ?></div>

<nav class="ss-nav">
    <div class="ss-brand">
        <img class="ss-brand-logo" src="<?= e(asset_url('img/shopsmart-logo.svg')) ?>" alt="ShopSmart">
        <div>
            <div class="ss-brand-name">ShopSmart</div>
            <div class="ss-brand-desc"><?= e($subTitle) ?></div>
        </div>
    </div>

    <div class="ss-nav-links">
        <a class="ss-nav-btn <?= (nav_active('/catalogo') || nav_active('/producto') || nav_active('/', true)) ? 'is-active' : '' ?>" href="<?= e(base_url('/catalogo')) ?>">Tienda</a>
        <?php if ($user !== null): ?>
            <a class="ss-nav-btn <?= nav_active('/carrito') ? 'is-active' : '' ?>" href="<?= e(base_url('/carrito')) ?>">Carrito</a>
        <?php endif; ?>
        <?php if ($isAdmin): ?>
            <a class="ss-nav-btn <?= nav_active('/admin') ? 'is-active' : '' ?>" href="<?= e(base_url('/admin/productos')) ?>">Admin</a>
        <?php endif; ?>
    </div>

    <div class="ss-nav-right">
        <?php if ($user === null): ?>
            <a class="ss-nav-btn <?= nav_active('/login', true) ? 'is-active' : '' ?>" href="<?= e(base_url('/login')) ?>">Iniciar sesion</a>
            <a class="ss-nav-btn ss-nav-btn-register" href="<?= e(base_url('/login#registro')) ?>">Registrarse</a>
        <?php else: ?>
            <span class="ss-nav-user"><?= e((string) ($user['nombre'] ?? 'Usuario')) ?></span>
            <form action="<?= e(base_url('/logout')) ?>" method="post" class="m-0">
                <?= csrf_field() ?>
                <button class="ss-nav-btn" type="submit">Salir</button>
            </form>
        <?php endif; ?>
    </div>
</nav>
