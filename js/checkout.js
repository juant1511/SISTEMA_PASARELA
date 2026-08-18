/* ============================================================
   ⚡ CHECKOUT JS ENGINE - Interacciones y Control de Métodos
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    const btnContinuar = document.getElementById('btnContinuar');
    const form = document.getElementById('checkoutForm');
    const modalSeguro = document.getElementById('modalSeguro');
    const overlaySeguro = document.getElementById('overlaySeguro');
    const btnCerrarModal = document.getElementById('btnCerrarModal');
    const btnPagarSeguro = document.getElementById('btnPagarSeguro');
    const pagoContra = document.getElementById('pagoContra');
    const pagoWompi = document.getElementById('pagoWompi');

    // Cambios de vista según método seleccionado
    if (pagoContra && pagoWompi) {
        pagoContra.addEventListener('change', function () {
            const itemSeguro = document.getElementById('itemSeguro');
            const bloqueContra = document.getElementById('bloqueContraentrega');
            if (itemSeguro) itemSeguro.style.display = 'none';
            if (bloqueContra) bloqueContra.style.display = 'block';
        });

        pagoWompi.addEventListener('change', function () {
            const itemSeguro = document.getElementById('itemSeguro');
            const bloqueContra = document.getElementById('bloqueContraentrega');
            if (itemSeguro) itemSeguro.style.display = 'none';
            if (bloqueContra) bloqueContra.style.display = 'none';
        });
    }

    // Modal Control
    function abrirModal() {
        if (modalSeguro) modalSeguro.classList.add('activo');
    }

    function cerrarModal() {
        if (modalSeguro) modalSeguro.classList.remove('activo');
    }

    if (btnCerrarModal) btnCerrarModal.addEventListener('click', cerrarModal);
    if (overlaySeguro) overlaySeguro.addEventListener('click', cerrarModal);

    // Botón Continuar
    if (btnContinuar && form) {
        btnContinuar.addEventListener('click', function (e) {
            e.preventDefault();

            // Validar campos del formulario HTML5
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const metodoSeleccionado = document.querySelector('input[name="metodo_pago"]:checked');
            const metodo = metodoSeleccionado ? metodoSeleccionado.value : 'contraentrega';

            if (metodo === 'contraentrega') {
                abrirModal();
            } else {
                if (typeof window.finalizarCompra === 'function') {
                    window.finalizarCompra(metodo);
                } else {
                    form.submit();
                }
            }
        });
    }

    // Botón Modal Pagar Seguro / Contraentrega
    if (btnPagarSeguro) {
        btnPagarSeguro.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Estado visual de carga en el botón
            btnPagarSeguro.disabled = true;
            btnPagarSeguro.style.opacity = '0.85';
            btnPagarSeguro.textContent = 'Generando orden...';

            const player = document.getElementById('modalDotLottie');
            if (player) {
                player.src = 'https://lottie.host/f0866ccb-1427-43b2-b07c-3850b36ce3f2/gqyfohuylF.lottie';
                try {
                    player.stop();
                    player.play();
                } catch (err) {}
            }

            // Esperar que termine la animación antes de proceder
            setTimeout(function () {
                cerrarModal();
                if (typeof window.finalizarCompra === 'function') {
                    window.finalizarCompra('seguro');
                } else {
                    form.submit();
                }
            }, 1600);
        });
    }
});
