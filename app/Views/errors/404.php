<section class="text-center py-5">
    <h1 class="display-6 fw-bold">Pagina no encontrada</h1>
    <p class="text-muted"><?= e($message ?? 'La ruta solicitada no existe o fue movida.') ?></p>
    <a class="btn btn-primary" href="<?= e(base_url('/catalogo')) ?>">Volver al catalogo</a>
</section>
