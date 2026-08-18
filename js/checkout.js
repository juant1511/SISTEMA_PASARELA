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
        const viewInitial = document.getElementById('modalViewInitial');
        const viewSuccess = document.getElementById('modalViewSuccess');
        const btnCerrar = document.getElementById('btnCerrarModal');

        if (viewInitial) {
            viewInitial.style.display = 'block';
            viewInitial.classList.remove('fading-out');
        }
        if (viewSuccess) {
            viewSuccess.style.display = 'none';
            viewSuccess.classList.remove('active');
        }
        if (btnCerrar) btnCerrar.style.display = 'block';
        if (btnPagarSeguro) {
            btnPagarSeguro.disabled = false;
            btnPagarSeguro.style.opacity = '1';
            btnPagarSeguro.textContent = 'Generar Orden';
        }

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
            
            // 1. Estado visual de carga en el botón
            btnPagarSeguro.disabled = true;
            btnPagarSeguro.style.opacity = '0.85';
            btnPagarSeguro.textContent = 'Generando orden...';

            const viewInitial = document.getElementById('modalViewInitial');
            const viewSuccess = document.getElementById('modalViewSuccess');
            const checkLottie = document.getElementById('modalCheckLottie');
            const btnCerrar = document.getElementById('btnCerrarModal');

            if (btnCerrar) btnCerrar.style.display = 'none';

            // 2. Desaparece el contenido de manera sutil
            if (viewInitial) {
                viewInitial.classList.add('fading-out');
            }

            // 3. Muestra grande en el centro la animación de check
            setTimeout(function () {
                if (viewInitial) viewInitial.style.display = 'none';
                if (viewSuccess) {
                    viewSuccess.style.display = 'block';
                    void viewSuccess.offsetWidth; // Forzar reflow para animación CSS
                    viewSuccess.classList.add('active');
                }
                if (checkLottie) {
                    try {
                        checkLottie.stop();
                        checkLottie.play();
                    } catch (err) {}
                }
            }, 320);

            // 4. Esperar que termine la animación de check antes de proceder al flujo normal
            setTimeout(function () {
                cerrarModal();
                if (typeof window.finalizarCompra === 'function') {
                    window.finalizarCompra('seguro');
                } else {
                    form.submit();
                }
            }, 2600);
        });
    }
});
