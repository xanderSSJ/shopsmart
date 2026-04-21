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
        <div class="ss-brand-icon">S</div>
        <div>
            <div class="ss-brand-name">ShopSmart</div>
            <div class="ss-brand-desc"><?= e($subTitle) ?></div>
        </div>
    </div>

    <div class="ss-nav-links">
        <a class="ss-nav-btn <?= (nav_active('/catalogo') || nav_active('/producto') || nav_active('/', true)) ? 'is-active' : '' ?>" href="<?= e(base_url('/catalogo')) ?>">Tienda</a>
        <a class="ss-nav-btn <?= nav_active('/login', true) ? 'is-active' : '' ?>" href="<?= e(base_url('/login')) ?>">Inicio sesion</a>
        <a class="ss-nav-btn <?= nav_active('/carrito') ? 'is-active' : '' ?>" href="<?= e(base_url('/carrito')) ?>">Carrito</a>
        <a class="ss-nav-btn <?= nav_active('/admin') ? 'is-active' : '' ?>" href="<?= e(base_url('/admin/productos')) ?>">Admin</a>
    </div>

    <div class="ss-nav-right">
        <span class="ss-nav-social">f</span>
        <span class="ss-nav-social">&#9716;</span>
        <span class="ss-nav-social">@</span>

        <span class="ss-nav-badge"><?= e($user !== null ? ($isAdmin ? 'Admin' : 'Usuario') : 'Invitado') ?></span>

        <?php if ($user !== null): ?>
            <form action="<?= e(base_url('/logout')) ?>" method="post" class="m-0">
                <?= csrf_field() ?>
                <button class="ss-nav-btn" type="submit">Salir</button>
            </form>
        <?php endif; ?>
    </div>
</nav>
