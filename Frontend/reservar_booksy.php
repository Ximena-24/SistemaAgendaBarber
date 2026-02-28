<?php
include_once "../conexion.php";
$idServicio = isset($_GET['servicio']) ? (int)$_GET['servicio'] : 0;

$sql = "SELECT NombreServicio, TimpoServicio, CostoServicio FROM TBL_BarberServicios WHERE IdServ = ?";
$stmt = sqlsrv_query($conn, $sql, [$idServicio]);
$servicioInfo = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$servicioInfo) {
    die("Servicio no válido");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar | Mexico Barber</title>
    <link href="../Img/LogoBarberMexico.png" rel="icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="../CSS/StyleReservar.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
</head>

<style>
    /* Botones de hora (Booksy style) */
    .btn-slot {
        border-radius: 16px;
        padding: 14px 12px;
        font-weight: 900;
        border: 1px solid rgba(0, 119, 182, .35);
        background: rgba(0, 119, 182, .12);
        color: var(--text);
        transition: transform .12s ease, background .12s ease, border-color .12s ease;
    }

    .btn-slot:hover {
        transform: translateY(-1px);
        background: rgba(230, 57, 70, .16);
        border-color: rgba(230, 57, 70, .55);
    }

    .btn-slot small {
        color: var(--muted);
        font-weight: 700;
    }

    /* Estilo para el aviso de fecha no disponible (Parecido a tu imagen) */
    .alert-premium {
        background: rgba(255, 243, 205, 0.1);
        /* Fondo crema muy sutil */
        border: 1px solid #BF953F;
        color: #f3d299;
        /* Texto dorado claro */
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Mejorar el calendario cuando se abre */
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        border-radius: 5px;
        padding: 2px;
        filter: invert(0.8) sepia(1) saturate(5) hue-rotate(10deg);
    }

    /* Ocultar el aviso por defecto con suavidad */
    #alerta-fecha {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Personalización de colores Flatpickr */
    .flatpickr-calendar {
        background: #140f36d8 !important;
        /* Tu azul oscuro */
        border: 1px solid #1c034e !important;
        /* Borde dorado */
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
    }

    /* Color de los días seleccionados */
    .flatpickr-day.selected {
        background: #17037a !important;
        border-color: #03036b !important;
        color: #fff !important;
    }

    /* Color de los nombres de los días (lu, ma, mi...) */
    .flatpickr-weekday {
        color: #ffffff !important;
        font-weight: bold;
    }

    /* Mes y año */
    .flatpickr-current-month {
        color: #EAF0FF !important;
    }

    /* Días de otros meses o bloqueados */
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: rgba(255, 255, 255, 0.1) !important;
    }


    /* Ajuste para que el logo tenga espacio cuando el texto desaparece */
    @media (max-width: 991px) {
        .navbar-brand {
            margin-right: 0;
            /* Quitamos margen si desaparece el texto */
        }

        .navbar img {
            margin-left: 10px;
            /* Le damos un toque de espacio al logo solito */
        }
    }
</style>

<style>
    :root {
        --gold-trim: #1200559d;
        --glass-dark: rgba(7, 4, 100, 0.4);
        /* Negro transparente */
        --glass-hover: rgba(69, 63, 191, 0.3);
        /* Dorado muy transparente para el hover */
    }

    /* ESTILO BASE PARA AMBOS BOTONES */
    .btn-nav-top-left,
    .btn-nav-bottom-right {
        position: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        background: var(--glass-dark);
        border: 1px solid rgba(25, 9, 117, 0.5);
        /* Borde dorado semi-transparente */
        color: var(--gold-trim);
        border-radius: 50%;
        backdrop-filter: blur(5px);
        /* Efecto de desenfoque detrás del botón */
        z-index: 10000;
        transition: all 0.3s ease;
    }

    /* POSICIÓN: ARRIBA IZQUIERDA */
    .btn-nav-top-left {
        top: 85px;
        /* Ajustado para que quede debajo de tu navbar */
        left: 20px;
        width: 40px;
        height: 40px;
    }

    /* POSICIÓN: ABAJO DERECHA (FLOTANTE) */
    .btn-nav-bottom-right {
        bottom: 30px;
        right: 25px;
        width: 55px;
        height: 55px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* EFECTOS HOVER */
    .btn-nav-top-left:hover,
    .btn-nav-bottom-right:hover {
        background: var(--gold-trim);
        color: #000;
        border-color: var(--gold-trim);
        transform: scale(1.1);
        box-shadow: 0 0 20px rgba(191, 149, 63, 0.4);
    }

    /* Ajuste para iconos */
    .btn-nav-top-left i {
        font-size: 16px;
    }

    .btn-nav-bottom-right i {
        font-size: 22px;
    }

    /* RESPONSIVE: Si en celular se ve muy grande, lo reducimos */
    @media (max-width: 576px) {
        .btn-nav-top-left {
            top: 75px;
            left: 15px;
        }

        .btn-nav-bottom-right {
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
        }
    }
</style>

<body class="pb-5">

    <a href="javascript:history.back()" class="btn-nav-top-left" title="Regresar">⬅️</a>

    <!-- Barra de navegacion colores -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <img src="../Img/BarberStudio.png" alt="" width="80">
            <!-- <a class="navbar-brand" href="index.php">Mexico Barber Studio</a> -->
            <a class="navbar-brand d-none d-lg-inline-block" href="index.php">Mexico Barber Studio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarMenu" aria-controls="navBarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navBarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="Servicios.php">✂️CORTES</a></li>
                    <li class="nav-item"><a class="nav-link" href="Info.php">💈NOSOTROS</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn-reservar" href="index.php">RESERVAR YA</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>
    <!-- Barra de navegacion colores -->

    <!-- Encabezado antes de calendario y Calendario -->
    <div class="container py-5">
        <div class="text-center">
            <!-- <img src="../Img/BarberStudio.png" alt="Logo" width="150"> -->
            <h2 class="mt-3"><strong>🗓️ PROGRAMA TU CITA EN </strong></h2>
            <h5>
                💈✂️ <span style="color: #19950e;"> LA MEXICO</span> BARBER
                <span style="color: #E63946;">STUDIO</span> ✂️💈
            </h5>
            <!-- <div class="service-badge">
                <span style="color rgb(255, 255, 255)57c; font-weight: bold;"><span style="color: #fcfcfc;">SERVICIO DE:</span> </strong> </span>
            </div> -->

            <div class="service-pill">
                <?= htmlspecialchars($servicioInfo['NombreServicio']) ?> &nbsp; -
                <div>⏱ <b><?= htmlspecialchars($servicioInfo['TimpoServicio']) ?></b></div> &nbsp; -
                <div>💲<b><?= (int)$servicioInfo['CostoServicio'] ?></b></div>
            </div>
        </div>
    </div>
    <!-- Encabezado antes de calendario y Calendario -->

    <!-- HORARIOS A SELECIONAR -->
    <div class="container">
        <div class="hero-card p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-badge">
                        <img src="../Img/BarberStudio.png" alt="" width="50">
                    </div>
                    <div>
                        <h4 class="mb-1 fw-black">SELECCIONA TU HORARIO</h4>
                        <!-- <div class="text-muted-soft">Selecciona una fecha y te mostraremos solo horas disponibles.</div> -->
                    </div>
                </div>
            </div>
            <div class="divider my-3"></div>

            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="label-premium"><strong> Selecciona Fecha:</strong></label>
                    <input type="date" id="fecha" class="form-control input-premium" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-12 col-md-7">
                    <div id="infoDuracion" class="mini-info"></div>
                </div>
            </div>

            <div class="mt-4" id="seccion-horarios">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <h5 class="m-0 fw-bold">Horas disponibles</h5>
                    <span class="tag-help">Toca una hora para reservar</span>
                </div>
                <div id="slots" class="row g-2"></div>
            </div>

            <div class="mt-4" id="seccion-horarios">
                <div id="alerta-fecha" class="alert-premium" style="display: none;">
                    ⚠️ Solo aceptamos citas con 24h de anticipación. Por favor, elige una fecha próxima.
                </div>
            </div>
        </div>
    </div>
    <!-- HORARIOS A SELECIONAR -->

    <!-- Modal de directorio y confirmacion de cliente -->
    <div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white" style="border:2px solid #0077B6;">
                <div class="modal-header border-0">
                    <h3 class="modal-title w-100 text-center">
                        <strong>CONFIRMACION DE CITA</strong>
                    </h3>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2 smallmuted modal-title w-100 text-center">Has seleccionado el siguiente horario para tu servicio:
                        <strong style="color:#E63946"><?= $servicioInfo['NombreServicio'] ?></strong>
                        <div id="resumen" class="fw-bold fs-5 text-info mb-3"></div>
                    </div>

                    <form action="../Backend/guardar_reserva.php" method="POST" autocomplete="off">
                        <input type="hidden" name="idServicio" value="<?= $idServicio ?>">
                        <input type="hidden" name="fecha_hora" id="fecha_hora">
                        <!-- ✅ AGREGA ESTOS DOS -->
                        <input type="hidden" name="nombreServicio" value="<?= htmlspecialchars($servicioInfo['NombreServicio']) ?>">
                        <input type="hidden" name="precioServicio" value="<?= (int)$servicioInfo['CostoServicio'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Teléfono (WhatsApp)</label>
                            <div class="input-group">
                                <select class="form-select" name="PaisCode" id="PaisCode" style="max-width:140px;">
                                    <option value="52" selected>+52 Mexico</option>
                                    <option value="1">+1 USA</option>
                                    <option value="55">+55 Brasil</option>
                                    <option value="57">+57 Colombia</option>
                                    <option value="34">+34 España</option>
                                    <option value="56">+56 Chile</option>
                                    <option value="51">+51 Perú</option>
                                    <option value="53">+53 Cuba</option>
                                    <option value="54">+54 Argentina</option>
                                    <option value="58">+58 Venezuela</option>
                                </select>
                                <input type="text" name="TelefonoCliente" id="TelefonoCliente" class="form-control" required
                                    placeholder="(ej. 4654567456)">
                            </div>
                            <div id="telHint" class="text-muted-soft mt-1" style="font-size:.85rem;"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="NombreCliente" id="NombreCliente" class="form-control" required placeholder="(ej. Juanito Lopez)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alias</label>
                            <input type="text" name="AliasCliente" id="AliasCliente" class="form-control" required placeholder="(ej. wicho)">
                        </div>

                        <!-- <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="CorreoCliente" id="CorreoCliente" class="form-control" placeholder="No requerido">
                        </div> -->

                        <button class="btn w-100" style="background:#E63946;color:#fff;font-weight:900;">
                            Confirmar reserva
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal de directorio y confirmacion de cliente -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const aviso = document.getElementById('alerta-fecha');
            const seccionHorarios = document.getElementById('seccion-horarios');
            const margen = 3; // 3 horas de anticipación
            const horaCierre = 20; // 8:00 PM

            // Inicializar el calendario moderno
            const fp = flatpickr("#fecha", {
                locale: "es",
                minDate: "today", // Bloquea ayer y días anteriores automáticamente
                dateFormat: "Y-m-d",
                disableMobile: "true", // Para que se vea el diseño pro también en celulares
                onChange: function(selectedDates, dateStr) {
                    validarFechaLocal(dateStr);
                }
            });

            function validarFechaLocal(fechaSeleccionada) {
                const ahora = new Date();

                // Obtener hoy en formato local YYYY-MM-DD
                const anio = ahora.getFullYear();
                const mes = String(ahora.getMonth() + 1).padStart(2, '0');
                const dia = String(ahora.getDate()).padStart(2, '0');
                const hoyLocal = `${anio}-${mes}-${dia}`;

                if (fechaSeleccionada === hoyLocal) {
                    const horaActual = ahora.getHours();

                    // Si son más de las 5:00 PM (17:00) y cierras a las 8:00 PM (20:00)
                    if (horaActual + margen >= horaCierre) {
                        mostrarError("⚠️ Para citas el día de hoy, se requiere un mínimo de 3 horas de anticipación.");
                        return;
                    }
                }
                ocultarError();
            }

            function mostrarError(mensaje) {
                aviso.innerHTML = mensaje;
                aviso.style.display = 'block';
                if (seccionHorarios) seccionHorarios.style.display = 'none';
            }

            function ocultarError() {
                aviso.style.display = 'none';
                if (seccionHorarios) seccionHorarios.style.display = 'block';
            }
        });
    </script>

    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputFecha = document.getElementById('fecha');
            const aviso = document.getElementById('alerta-fecha');
            const seccionHorarios = document.getElementById('seccion-horarios');

            function validarDisponibilidad() {
                const ahora = new Date();

                // --- LA CORRECCIÓN ESTÁ AQUÍ ---
                // Obtenemos año, mes y día local, asegurando el formato YYYY-MM-DD
                const anio = ahora.getFullYear();
                const mes = String(ahora.getMonth() + 1).padStart(2, '0');
                const dia = String(ahora.getDate()).padStart(2, '0');
                const hoySoloFecha = `${anio}-${mes}-${dia}`;

                const seleccionadaSoloFecha = inputFecha.value;

                // 1. Bloqueo de días pasados
                if (seleccionadaSoloFecha < hoySoloFecha) {
                    mostrarError("⚠️ No puedes seleccionar una fecha que ya pasó.");
                    return;
                }

                // 2. Regla de 3 horas para el día de HOY
                if (seleccionadaSoloFecha === hoySoloFecha) {
                    const horaActual = ahora.getHours();
                    const margen = 3; // 3 horas anticipadas 
                    const horaCierre = 20; // 8:00 PM

                    if (horaActual + margen >= horaCierre) {
                        mostrarError("⚠️ Para citas el día de hoy, se requiere un mínimo de 3 horas de anticipación.");
                        return;
                    }
                }

                // Si todo está bien
                ocultarError();
            }

            // ... (tus funciones mostrarError y ocultarError se quedan igual)
            function mostrarError(mensaje) {
                aviso.innerHTML = mensaje;
                aviso.style.display = 'block';
                if (seccionHorarios) {
                    seccionHorarios.style.opacity = '0';
                    setTimeout(() => {
                        seccionHorarios.style.display = 'none';
                    }, 300);
                }
            }

            function ocultarError() {
                aviso.style.display = 'none';
                if (seccionHorarios) {
                    seccionHorarios.style.display = 'block';
                    setTimeout(() => {
                        seccionHorarios.style.opacity = '1';
                    }, 10);
                }
            }

            inputFecha.addEventListener('change', validarDisponibilidad);
            validarDisponibilidad();
        });
    </script> -->

    <script>
        function fmtAMPM(hm) {
            const [h, m] = hm.split(':').map(Number);
            const ampm = h >= 12 ? 'p.m.' : 'a.m.';
            let hh = h % 12;
            if (hh === 0) hh = 12;
            return `${hh}:${String(m).padStart(2,'0')} ${ampm}`;
        }
    </script>

    <script>
        const ID_SERVICIO = <?= $idServicio ?>;

        function cargarSlots() {
            const f = document.getElementById('fecha').value;
            fetch(`../API/api_horas_libres.php?servicio=${ID_SERVICIO}&fecha=${f}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('infoDuracion').innerText =
                        `Duración: ${data.duracion} min · Intervalo: ${data.base} min`;

                    const cont = document.getElementById('slots');
                    cont.innerHTML = "";

                    if (!data.horas || data.horas.length === 0) {
                        cont.innerHTML = `<div class="col-12"><div class="alert alert-warning m-0">
          No hay horarios disponibles para esta fecha.
        </div></div>`;
                        return;
                    }

                    data.horas.forEach(x => {
                        const col = document.createElement('div');
                        col.className = "col-6 col-md-3";
                        col.innerHTML = `
  <button class="btn btn-slot w-100" data-h="${x.hora}">
    <div class="d-flex justify-content-between align-items-center">
      <div class="fw-black">${fmtAMPM(x.hora)}</div>
          <span class="slot-icon">🕒</span>
      <small> ${fmtAMPM(x.horaFin)}</small>
    </div>
  </button>
`;
                        col.querySelector('button').onclick = () => abrirModal(f, x.hora);
                        cont.appendChild(col);
                    });
                });
        }

        function abrirModal(fecha, hora) {
            // 1. Guardamos el valor original para el input hidden del formulario
            const dtStr = `${fecha}T${hora}:00`;
            document.getElementById('fecha_hora').value = dtStr;

            // 2. Creamos un objeto Date para formatear (usamos / para evitar desfases de zona horaria en algunos navegadores)
            const fechaObj = new Date(fecha.replace(/-/g, '\/') + ' ' + hora);

            // 3. Configuramos las opciones del formato de fecha
            const opciones = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            // 4. Construimos el texto final
            // Convertimos la fecha a: "viernes, 6 de febrero de 2026"
            const fechaLarga = fechaObj.toLocaleDateString('es-ES', opciones);

            // Usamos tu función fmtAMPM que ya tienes definida para la hora
            const horaFormateada = fmtAMPM(hora);

            // 5. Inyectamos el resultado (asumí 30 min por tu mensaje anterior, pero puedes hacerlo dinámico)
            document.getElementById('resumen').innerText = `${fechaLarga}, ${horaFormateada}`;

            // 6. Mostramos el modal
            new bootstrap.Modal(document.getElementById('modalReserva')).show();
        }

        document.getElementById('fecha').addEventListener('change', cargarSlots);
        cargarSlots();
    </script>

    <script>
        function soloDigitos(v) {
            return (v || '').replace(/\D+/g, '');
        }

        function lenNacionalByPais(paisCode) {
            // Ajusta si quieres: MX 10, USA 10. La mayoría 10 por default aquí.
            if (paisCode === "52") return 10;
            if (paisCode === "1") return 10;
            return 10;
        }

        let timerTel = null;

        const telInput = document.getElementById('TelefonoCliente');
        const paisSelect = document.getElementById('PaisCode');
        const hint = document.getElementById('telHint');

        function buscarTelefono() {
            const tel = soloDigitos(telInput.value);
            telInput.value = tel;

            const pais = (paisSelect.value || "").trim();
            const minLen = lenNacionalByPais(pais);

            clearTimeout(timerTel);

            if (tel.length < minLen) {
                hint.innerText = `Escribe al menos ${minLen} dígitos...`;
                return;
            }

            timerTel = setTimeout(() => {
                const url = `../API/api_buscar_cliente.php?telefono=${encodeURIComponent(tel)}&pais=${encodeURIComponent(pais)}`;

                fetch(url, {
                        cache: "no-store"
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.ok) {
                            hint.innerText = "⚠️ No se pudo validar el teléfono.";
                            return;
                        }

                        if (data.found) {
                            document.getElementById('NombreCliente').value = data.cliente.NombreCliente || '';
                            document.getElementById('AliasCliente').value = data.cliente.AliasCliente || '';
                            // document.getElementById('CorreoCliente').value = data.cliente.CorreoCliente || '';
                            hint.innerText = "✅ Cliente encontrado, datos cargados.";
                        } else {
                            hint.innerText = "🆕 Teléfono nuevo, se registrará en el directorio.";
                        }
                    })
                    .catch(() => hint.innerText = "⚠️ Error de red al buscar teléfono.");
            }, 300);
        }

        telInput.addEventListener('input', buscarTelefono);
        paisSelect.addEventListener('change', buscarTelefono); // si cambia país, vuelve a buscar
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>