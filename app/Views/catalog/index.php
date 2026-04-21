<?php $user = auth_user(); ?>

<section class="ss-hero">
    <div>
        <h1>Nueva temporada ShopSmart</h1>
        <p>Catalogo, compra simulada y panel admin en una interfaz clara inspirada en tus mockups.</p>
        <a class="ss-hero-cta" href="#catalogo">Ver catalogo</a>
    </div>
    <div class="ss-hero-art"></div>
</section>

<div class="ss-sale">
    <span>2026 SPRING SALE</span>
    <span class="ss-sale-badge">Get Up To 30% OFF</span>
</div>

<div class="ss-catalog-grid" id="catalogo">
    <aside class="ss-panel">
        <h2 class="ss-block-title">Filtros de tienda</h2>
        <form action="<?= e(base_url('/catalogo')) ?>" method="get" class="vstack gap-2">
            <div>
                <label class="form-label">Buscar productos</label>
                <input type="text" class="form-control" name="q" value="<?= e((string) ($search ?? '')) ?>" placeholder="Buscar...">
            </div>

            <div>
                <label class="form-label">Categoria</label>
                <select class="form-select" name="categoria">
                    <option value="">Todas</option>
                    <?php foreach (($categories ?? []) as $category): ?>
                        <?php $categoryName = (string) ($category['nombre'] ?? ''); ?>
                        <option value="<?= e($categoryName) ?>" <?= (($selectedCategory ?? '') === $categoryName) ? 'selected' : '' ?>><?= e($categoryName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn ss-btn-blue">Buscar</button>
            <a href="<?= e(base_url('/catalogo')) ?>" class="btn ss-btn-muted">Limpiar</a>
        </form>
    </aside>

    <section class="ss-panel">
        <h2 class="ss-panel-title">Catalogo de productos</h2>

        <?php if (count($products) === 0): ?>
            <div class="ss-placeholder">No se encontraron productos con los filtros seleccionados.</div>
        <?php else: ?>
            <div class="ss-product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="ss-product-card">
                        <img class="ss-product-img" src="<?= e($product['imagen_url'] ?: 'https://placehold.co/640x400?text=ShopSmart') ?>" alt="<?= e($product['nombre']) ?>">
                        <div class="ss-product-body">
                            <h3 class="ss-product-name"><?= e($product['nombre']) ?></h3>
                            <div class="ss-product-meta"><?= e($product['categoria_nombre']) ?> | Stock: <?= e((string) $product['stock']) ?></div>
                            <p class="ss-product-meta"><?= e($product['descripcion']) ?></p>
                            <div class="ss-product-price">$<?= e(number_format((float) $product['precio'], 2)) ?></div>

                            <div class="d-flex gap-2 flex-wrap">
                                <a class="btn ss-btn-muted" href="<?= e(base_url('/producto/' . $product['id_producto'])) ?>">Ver detalle</a>

                                <?php if ($user !== null): ?>
                                    <form action="<?= e(base_url('/carrito/agregar')) ?>" method="post" class="d-flex gap-2 ms-auto">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="producto_id" value="<?= e((string) $product['id_producto']) ?>">
                                        <input type="number" name="cantidad" value="1" min="1" max="<?= e((string) $product['stock']) ?>" class="form-control quantity-input">
                                        <button class="btn ss-btn-teal" type="submit">Agregar</button>
                                    </form>
                                <?php else: ?>
                                    <a class="btn ss-btn-teal ms-auto" href="<?= e(base_url('/login')) ?>">Inicia sesion</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
