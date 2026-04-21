<?php $defaultProductImage = asset_url('img/product-placeholder.svg'); ?>

<section class="ss-banner ss-banner-cart">
    <h2>Tu carrito en un solo lugar</h2>
    <p>Actualiza cantidades, elimina productos y confirma tu compra simulada.</p>
</section>

<?php if (count($items) === 0): ?>
    <section class="ss-panel">
        <h3 class="ss-block-title">Resumen de carrito</h3>
        <div class="ss-placeholder">Tu carrito esta vacio. Ve a tienda para agregar productos.</div>
        <a href="<?= e(base_url('/catalogo')) ?>" class="btn ss-btn-blue mt-3">Ir a tienda</a>
    </section>

    <section class="ss-panel mb-0">
        <h3 class="ss-block-title">Mis pedidos recientes</h3>
        <div class="ss-placeholder">Aun no hay pedidos. Al confirmar una compra apareceran aqui.</div>
    </section>
<?php else: ?>
    <section class="ss-panel">
        <h3 class="ss-block-title">Resumen de carrito</h3>

        <div class="ss-table-wrap">
            <table class="ss-table">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= e($item['imagen_url'] ?: $defaultProductImage) ?>" alt="<?= e($item['nombre']) ?>" class="cart-thumb">
                                <span><?= e($item['nombre']) ?></span>
                            </div>
                        </td>
                        <td>$<?= e(number_format((float) $item['precio_unitario'], 2)) ?></td>
                        <td>
                            <form action="<?= e(base_url('/carrito/actualizar')) ?>" method="post" class="d-flex align-items-center gap-2 flex-wrap">
                                <?= csrf_field() ?>
                                <input type="hidden" name="detalle_id" value="<?= e((string) $item['id_detalle_carrito']) ?>">
                                <input type="number" name="cantidad" value="<?= e((string) $item['cantidad']) ?>" min="1" max="<?= e((string) $item['stock']) ?>" class="form-control quantity-input">
                                <button class="btn ss-btn-blue" type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?= e(number_format((float) $item['subtotal'], 2)) ?></td>
                        <td>
                            <form action="<?= e(base_url('/carrito/eliminar')) ?>" method="post" class="delete-form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="detalle_id" value="<?= e((string) $item['id_detalle_carrito']) ?>">
                                <button class="btn ss-btn-muted" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ss-total">
            <div>
                <div class="ss-muted">Total de compra simulada</div>
                <strong>$<?= e(number_format((float) $total, 2)) ?></strong>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= e(base_url('/catalogo')) ?>" class="btn ss-btn-muted">Seguir comprando</a>
                <form action="<?= e(base_url('/checkout')) ?>" method="post" class="checkout-form">
                    <?= csrf_field() ?>
                    <button class="btn ss-btn-blue" type="submit">Confirmar compra simulada</button>
                </form>
            </div>
        </div>
    </section>

    <section class="ss-panel mb-0">
        <h3 class="ss-block-title">Mis pedidos recientes</h3>
        <div class="ss-placeholder">Revisa todos tus pedidos en <a href="<?= e(base_url('/mis-pedidos')) ?>">Mis pedidos</a>.</div>
    </section>
<?php endif; ?>
