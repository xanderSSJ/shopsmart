document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm('Seguro que deseas realizar esta accion?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('.checkout-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm('Confirmas la compra simulada?')) {
                event.preventDefault();
            }
        });
    });
});
