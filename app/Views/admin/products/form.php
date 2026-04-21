<section class="ss-panel">
    <div class="ss-admin-head">
        <h1 class="ss-admin-title"><?= e($title ?? 'Formulario de producto') ?></h1>
        <a class="btn ss-btn-muted" href="<?= e(base_url('/admin/productos')) ?>">Volver al listado</a>
    </div>

    <form action="<?= e(base_url($action)) ?>" method="post" class="row g-3">
        <?= csrf_field() ?>

        <div class="col-12 col-md-6">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" value="<?= e((string) ($product['nombre'] ?? '')) ?>" required>
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Categoria</label>
            <select class="form-select" name="id_categoria" required>
                <option value="">Selecciona...</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= e((string) $category['id_categoria']) ?>" <?= ((int) ($product['id_categoria'] ?? 0) === (int) $category['id_categoria']) ? 'selected' : '' ?>>
                        <?= e($category['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Descripcion</label>
            <textarea class="form-control" rows="4" name="descripcion"><?= e((string) ($product['descripcion'] ?? '')) ?></textarea>
        </div>

        <div class="col-12 col-md-3">
            <label class="form-label">Precio</label>
            <input class="form-control" type="number" step="0.01" min="0.01" name="precio" value="<?= e((string) ($product['precio'] ?? '0.00')) ?>" required>
        </div>

        <div class="col-12 col-md-3">
            <label class="form-label">Stock</label>
            <input class="form-control" type="number" step="1" min="0" name="stock" value="<?= e((string) ($product['stock'] ?? '0')) ?>" required>
        </div>

        <div class="col-12 col-md-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado" required>
                <option value="activo" <?= (($product['estado'] ?? 'activo') === 'activo') ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= (($product['estado'] ?? '') === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>

        <div class="col-12 col-md-3">
            <label class="form-label">Imagen URL</label>
            <input class="form-control" type="url" name="imagen_url" value="<?= e((string) ($product['imagen_url'] ?? '')) ?>" placeholder="https://...">
        </div>

        <div class="col-12 d-flex gap-2 flex-wrap">
            <button class="btn ss-btn-blue" type="submit"><?= e($submitLabel ?? 'Guardar') ?></button>
            <a class="btn ss-btn-muted" href="<?= e(base_url('/admin/productos')) ?>">Cancelar</a>
        </div>
    </form>
</section>
