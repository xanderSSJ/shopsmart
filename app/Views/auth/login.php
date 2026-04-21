<?php $user = auth_user(); ?>

<section class="ss-panel">
    <div class="ss-user-card">
        <div class="ss-user-left">
            <div class="ss-avatar">X</div>
            <div>
                <p class="ss-user-name"><?= e($user['nombre'] ?? 'Invitado') ?></p>
                <div class="ss-user-email"><?= e($user['email'] ?? 'Sin sesion iniciada') ?></div>
            </div>
        </div>
        <div class="ss-balance">
            <div class="ss-balance-label">Saldo</div>
            <div class="ss-balance-value">$3299.00</div>
        </div>
    </div>
    <?php if ($user === null): ?>
        <a class="btn ss-btn-teal w-100 mt-3" href="<?= e(base_url('/login')) ?>">Inicia sesion</a>
    <?php else: ?>
        <a class="btn ss-btn-blue w-100 mt-3" href="<?= e(base_url('/catalogo')) ?>">Ir a la tienda</a>
    <?php endif; ?>
</section>

<section class="ss-banner ss-banner-user">
    <h1>Acceso de usuario</h1>
    <p>Registra tu cuenta, inicia sesion y consulta tus pedidos desde esta pagina.</p>
</section>

<section class="ss-panel">
    <h2 class="ss-panel-title">Registro e inicio de sesion</h2>

    <div class="ss-form-grid">
        <div>
            <h3 class="ss-block-title">Registro</h3>
            <form action="<?= e(base_url('/register')) ?>" method="post" class="vstack gap-2">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="tucorreo@email.com" required>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" minlength="8" placeholder="Minimo 8 caracteres" required>
                </div>
                <button class="btn ss-btn-teal" type="submit">Crear cuenta</button>
            </form>
            <div class="ss-note mt-2">Admin demo: admin@shopsmart.local / Admin123!</div>
        </div>

        <div>
            <h3 class="ss-block-title">Inicio de sesion</h3>
            <form action="<?= e(base_url('/login')) ?>" method="post" class="vstack gap-2">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="tucorreo@email.com" required>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Tu password" required>
                </div>
                <button class="btn ss-btn-orange" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</section>

<section class="ss-panel">
    <h3 class="ss-block-title">Datos del usuario</h3>
    <?php if ($user !== null): ?>
        <div class="ss-placeholder">Sesion activa: <?= e($user['nombre']) ?> (<?= e($user['email']) ?>)</div>
    <?php else: ?>
        <div class="ss-placeholder">No has iniciado sesion.</div>
    <?php endif; ?>
</section>

<section class="ss-panel mb-0">
    <h3 class="ss-block-title">Pedidos del usuario</h3>
    <?php if ($user !== null): ?>
        <div class="ss-placeholder">Puedes revisar tu historial en <a href="<?= e(base_url('/mis-pedidos')) ?>">Mis pedidos</a>.</div>
    <?php else: ?>
        <div class="ss-placeholder">Inicia sesion para ver tus pedidos.</div>
    <?php endif; ?>
</section>
