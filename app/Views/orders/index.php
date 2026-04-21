<section class="ss-banner ss-banner-cart">
    <h2>Pedidos simulados</h2>
    <p>Historial de compras registradas en tu cuenta.</p>
</section>

<?php if (count($orders) === 0): ?>
    <section class="ss-panel">
        <div class="ss-placeholder">Aun no tienes pedidos registrados.</div>
    </section>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <section class="ss-panel">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div>
                    <h3 class="ss-block-title mb-1">Pedido #<?= e((string) $order['id_pedido']) ?></h3>
                    <div class="ss-muted">Fecha: <?= e((string) $order['fecha_pedido']) ?></div>
                </div>
                <div class="text-end">
                    <div class="ss-muted">Estado: <?= e((string) $order['estado']) ?></div>
                    <div class="ss-product-price mb-0">$<?= e(number_format((float) $order['total'], 2)) ?></div>
                </div>
            </div>

            <div class="ss-table-wrap">
                <table class="ss-table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= e($item['producto_nombre']) ?></td>
                            <td><?= e((string) $item['cantidad']) ?></td>
                            <td>$<?= e(number_format((float) $item['precio_unitario'], 2)) ?></td>
                            <td>$<?= e(number_format((float) $item['subtotal'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>
