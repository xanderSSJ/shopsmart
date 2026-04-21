<?php $defaultProductImage = asset_url('img/product-placeholder.svg'); ?>

<section class="ss-product-layout">
    <div>
        <div class="ss-product-mainimg">
            <img src="<?= e($product['imagen_url'] ?: $defaultProductImage) ?>" alt="<?= e($product['nombre']) ?>">
        </div>

        <div class="ss-thumbs">
            <div class="ss-thumb"><img src="<?= e($product['imagen_url'] ?: $defaultProductImage) ?>" alt="thumb"></div>
            <div class="ss-thumb"><img src="<?= e($product['imagen_url'] ?: $defaultProductImage) ?>" alt="thumb"></div>
            <div class="ss-thumb"><img src="<?= e($product['imagen_url'] ?: $defaultProductImage) ?>" alt="thumb"></div>
        </div>
    </div>

    <div>
        <h1 class="ss-product-title"><?= e($product['nombre']) ?></h1>
        <div class="ss-stars">&#9733; &#9733; &#9733; &#9733; &#9734; (123 Resenas)</div>

        <p class="ss-muted"><?= e($product['descripcion']) ?></p>
        <div class="ss-price-red">$<?= e(number_format((float) $product['precio'], 2)) ?></div>

        <?php if (auth_user() !== null): ?>
            <form action="<?= e(base_url('/carrito/agregar')) ?>" method="post" class="d-flex gap-2 align-items-center flex-wrap mt-3">
                <?= csrf_field() ?>
                <input type="hidden" name="producto_id" value="<?= e((string) $product['id_producto']) ?>">
                <label class="form-label mb-0">Cantidad:</label>
                <input type="number" name="cantidad" value="1" min="1" max="<?= e((string) $product['stock']) ?>" class="form-control quantity-input">
                <button class="btn ss-btn-blue" type="submit">Anadir al carrito</button>
                <a class="btn ss-btn-muted" href="<?= e(base_url('/catalogo')) ?>">Volver</a>
            </form>
        <?php else: ?>
            <div class="d-flex gap-2 flex-wrap mt-3">
                <a class="btn ss-btn-blue" href="<?= e(base_url('/login')) ?>">Inicia sesion para comprar</a>
                <a class="btn ss-btn-muted" href="<?= e(base_url('/catalogo')) ?>">Volver</a>
            </div>
        <?php endif; ?>

        <p class="ss-muted mt-3">Categoria: <?= e($product['categoria_nombre']) ?> | Stock disponible: <?= e((string) $product['stock']) ?></p>
    </div>
</section>

<section class="ss-detail-section mt-4">
    <h2>Detalles del Producto</h2>
    <p>
        Descripcion detallada del producto con informacion util para la compra.
        Esta seccion sigue el estilo de tu mockup para facilitar la lectura.
    </p>
</section>

<section class="ss-detail-section mt-4">
    <h2>Resenas de Clientes</h2>
    <article class="ss-review-card">
        <div class="ss-review-title">Juan Perez &#9733; &#9733; &#9733; &#9733; &#9733;</div>
        <p class="mb-0">"Excelente producto, buena calidad y entrega rapida."</p>
    </article>
    <article class="ss-review-card">
        <div class="ss-review-title">Maria Garcia &#9733; &#9733; &#9733; &#9733; &#9734;</div>
        <p class="mb-0">"Buen producto, aunque esperaba otro tono de color."</p>
    </article>
</section>
