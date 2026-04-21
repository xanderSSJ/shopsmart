<section class="ss-panel">
    <div class="ss-admin-head">
        <h1 class="ss-admin-title">Panel Admin - Productos</h1>
        <a class="btn ss-btn-blue" href="<?= e(base_url('/admin/productos/crear')) ?>">Nuevo Producto</a>
    </div>

    <form action="<?= e(base_url('/admin/productos')) ?>" method="get" class="d-flex gap-2 align-items-center mb-3 flex-wrap">
        <input class="form-control" style="max-width: 520px;" name="q" value="<?= e((string) ($search ?? '')) ?>" placeholder="Buscar productos..." type="text">
        <button class="btn ss-btn-blue" type="submit">Buscar</button>
        <a class="btn ss-btn-muted" href="<?= e(base_url('/admin/productos')) ?>">Limpiar</a>
    </form>

    <div class="ss-table-wrap">
        <table class="ss-table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= e($product['nombre']) ?></td>
                    <td><?= e($product['categoria_nombre']) ?></td>
                    <td>$<?= e(number_format((float) $product['precio'], 2)) ?></td>
                    <td><?= e((string) $product['stock']) ?></td>
                    <td><?= e(ucfirst((string) $product['estado'])) ?></td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="btn ss-btn-blue" href="<?= e(base_url('/admin/productos/' . $product['id_producto'] . '/editar')) ?>">Editar</a>
                            <form action="<?= e(base_url('/admin/productos/' . $product['id_producto'] . '/eliminar')) ?>" method="post" class="delete-form">
                                <?= csrf_field() ?>
                                <button class="btn ss-btn-muted" type="submit">Desactivar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
