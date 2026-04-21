<?php $user = auth_user(); ?>

<section class="ss-banner ss-banner-user">
    <h1>Acceso de usuario</h1>
    <p>Registra tu cuenta y luego entra como usuario o como admin segun tu perfil.</p>
</section>

<section class="ss-panel ss-login-panel">
    <h2 class="ss-panel-title">Registro e inicio de sesion</h2>

    <div class="ss-form-grid">
        <div id="registro">
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

        <div id="login">
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
                <div>
                    <label class="form-label">Tipo de acceso</label>
                    <select name="acceso" class="form-select" required>
                        <option value="usuario">Usuario</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button class="btn ss-btn-orange" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</section>

<?php if ($user !== null): ?>
    <section class="ss-panel mb-0">
        <h3 class="ss-block-title">Sesion activa</h3>
        <div class="ss-placeholder">Has iniciado sesion como <?= e((string) $user['nombre']) ?> (<?= e((string) $user['email']) ?>).</div>
    </section>
<?php endif; ?>
