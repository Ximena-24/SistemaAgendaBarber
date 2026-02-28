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
    body {
        background: #001D3D;
        color: #F1FAEE;
    }

    .cardbox {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 18px;
    }

    .btn-slot {
        border-radius: 14px;
        padding: 14px 12px;
        font-weight: 800;
        background: rgba(0, 119, 182, .18);
        color: #fff;
        border: 1px solid rgba(0, 119, 182, .35);
        transition: .15s;
    }

    .btn-slot:hover {
        transform: translateY(-1px);
        background: rgba(230, 57, 70, .18);
        border-color: rgba(230, 57, 70, .6);
    }

    .badge-service {
        background: #E63946;
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 800;
    }

    .smallmuted {
        color: rgba(255, 255, 255, .75);
    }




    /* SEGUNDO ESTILO */

    :root {
        --blue-dark: #001D3D;
        --blue-light: #0077B6;
        --red-main: #04065dba;
        --white: #F1FAEE;
    }

    body {
        background-color: var(--blue-dark);
        color: var(--white);
        font-family: 'Montserrat', sans-serif;
    }

    .calendar-container {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-top: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }


    /* Estilos para que el calendario se vea elegante y moderno */
    .fc {
        /* Base de FullCalendar */
        --fc-border-color: rgba(255, 255, 255, 0.1);
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: rgba(0, 0, 0, 0.2);
        --fc-list-event-hover-bg-color: var(--blue-light);
    }

    .fc-col-header-cell {
        background: var(--blue-light);
        color: white;
        padding: 10px 0;
    }

    .fc-timegrid-slot {
        height: 3em !important;
        border-bottom: 1px dotted rgba(255, 255, 255, 0.05) !important;
    }

    .fc-event {
        background-color: var(--red-main) !important;
        border: none !important;
        cursor: pointer;
    }

    .fc-toolbar-title {
        font-weight: 800;
        text-transform: uppercase;
    }

    .fc-button-primary {
        background-color: var(--blue-light) !important;
        border: none !important;
    }

    .fc-button-primary:hover {
        background-color: var(--red-main) !important;
    }

    .service-badge {
        background: var(--red-main);
        padding: 10px 20px;
        border-radius: 50px;
        display: inline-block;
        margin-bottom: 20px;
    }



    /* Color de los números y días en la cabecera */
    .fc .fc-col-header-cell-cushion {
        color: #FFFFFF !important;
        text-transform: capitalize;
        padding: 8px 0;
        display: block;
    }

    /* Color de las horas en el lateral (8 AM, 9 AM...) */
    .fc .fc-timegrid-slot-label-cushion {
        color: #FFFFFF !important;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* Color del título principal (Mes y Año) */
    .fc .fc-toolbar-title {
        color: var(--accent) !important;
        font-size: 1.8rem !important;
    }

    /* Fondo de las celdas de tiempo para mejor contraste */
    .fc-timegrid-slot {
        background: rgba(255, 255, 255, 0.02);
    }

    /* Líneas divisorias más marcadas */
    .fc .fc-timegrid-divider {
        display: none;
    }

    .fc td,
    .fc th {
        border-style: solid !important;
    }

    /* Color de los botones (Hoy, Semana, Día) */
    .fc .fc-button {
        font-weight: bold !important;
        text-transform: uppercase !important;
    }





    /* ESTILO BARRA DE NAVEGACION */

    :root {
        --barber-red: #E63946;
        --barber-blue: #0056b3;
        --barber-white: #ffffff;
        --dark-bg: #121212;
        /* Fondo negro elegante */
    }

    /* BARRA SUPERIOR ANIMADA (EL DETALLE DEL BARBER POLE) */
    .navbar::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        /* Una línea delgada muy pro */
        background: repeating-linear-gradient(-45deg,
                var(--barber-red) 0%, var(--barber-red) 10%,
                var(--barber-white) 10%, var(--barber-white) 20%,
                var(--barber-blue) 20%, var(--barber-blue) 30%);
        background-size: 200% 100%;
        animation: barberFlow 5s linear infinite;
    }

    .navbar {
        /* background: #001D3D; */
        background: rgba(0, 22, 56, 0.95) !important;
        /* Fondo oscuro transparente */
        backdrop-filter: blur(10px);
        /* Efecto esmerilado */
        border-bottom: 2px solid #BF953F;
        /* Línea dorada delgada abajo */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        padding: 10px 0;
    }

    @keyframes barberFlow {
        0% {
            background-position: 0% 0%;
        }

        100% {
            background-position: 100% 0%;
        }
    }

    /* LOGO DORADO MEJORADO */
    .navbar-brand {
        font-family: 'Bebas Neue', 'Impact', sans-serif;
        font-size: 2.2rem !important;
        font-weight: 900;
        text-transform: uppercase;
        background: linear-gradient(to bottom, #BF953F 20%, #FCF6BA 45%, #B38728 70%, #AA771C 85%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(2px 2px 0px #000);
        transform: skew(-10deg);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .navbar-brand:hover {
        transform: skew(-10deg) scale(1.1) rotate(-2deg);
        filter: brightness(1.3) drop-shadow(4px 4px 0px #000);
    }

    /* ENLACES DEL MENÚ MODERNOS */
    .nav-link {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #909090 !important;
        margin: 0 10px;
        position: relative;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    /* Animación de línea dorada al pasar el mouse */
    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        background: #BF953F;
        left: 0;
        bottom: -5px;
        transition: 0.4s;
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .nav-link:hover {
        color: #BF953F !important;
    }

    /* BOTÓN RESERVAR CON PULSACIÓN */
    .btn-reservar {
        background: #bf943f88 !important;
        color: #000 !important;
        font-weight: 800 !important;
        border-radius: 0px !important;
        /* Estilo vintage cuadrado */
        transform: skew(-10deg);
        padding: 8px 20px !important;
        box-shadow: 4px 4px 0px #000;
        animation: pulseGold 2s infinite;
    }

    @keyframes pulseGold {
        0% {
            box-shadow: 0 0 0 0 rgba(191, 149, 63, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(191, 149, 63, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(191, 149, 63, 0);
        }
    }

    /* MENU MÓVIL */
    .navbar-toggler {
        border: 1px solid #BF953F !important;
    }

    .navbar-toggler-icon {
        filter: invert(1);
        /* Hace la hamburguesa blanca */
    }

    @media (max-width: 991px) {
        .navbar-collapse {
            background: #121212;
            padding: 20px;
            text-align: center;
        }
    }





    /* ⭐ Diseño profesional Booksy */

    .fc-timegrid-slot {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        transition: 0.2s;
    }

    .fc-timegrid-slot:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    /* ⭐ Hora lateral estilo Booksy */
    .hora-booksy {
        font-size: 0.9rem;
        font-weight: 700;
        color: #FFFFFF;
        text-transform: lowercase;
    }

    /* ⭐ Línea roja actual */
    .fc .fc-timegrid-now-indicator-line {
        border-color: #E63946 !important;
        border-width: 2px;
    }

    /* ⭐ Eventos estilo tarjeta */
    .fc-event {
        border-radius: 8px !important;
        padding: 4px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    /* ⭐ Hover slot */
    .fc-timegrid-slot-lane:hover {
        background: rgba(0, 119, 182, 0.2);
    }






    /* ESTILO DE HORARIOS A SELECIONAR */
    :root {
        --bg: #001D3D;
        --card: rgba(255, 255, 255, .06);
        --stroke: rgba(255, 255, 255, .12);
        --text: #F1FAEE;
        --muted: rgba(241, 250, 238, .72);
        --blue: #0077B6;
        --red: #E63946;
    }

    .hero-card {
        background: var(--card);
        border: 1px solid var(--stroke);
        border-radius: 22px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .35);
        backdrop-filter: blur(10px);
    }

    .logo-badge {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, rgba(0, 119, 182, .35), rgba(230, 57, 70, .25));
        border: 1px solid rgba(255, 255, 255, .14);
    }

    .fw-black {
        font-weight: 900;
    }

    .text-muted-soft {
        color: var(--muted);
    }

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .16), transparent);
    }

    .service-pill {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem .95rem;
        border-radius: 999px;
        font-weight: 900;
        letter-spacing: .2px;
        background: rgba(230, 57, 70, .18);
        border: 1px solid rgba(230, 57, 70, .45);
    }

    .input-premium {
        background: rgba(255, 255, 255, .07) !important;
        border: 1px solid rgba(255, 255, 255, .14) !important;
        color: var(--text) !important;
        border-radius: 14px !important;
        padding: .85rem .9rem !important;
    }

    .input-premium:focus {
        box-shadow: 0 0 0 .25rem rgba(0, 119, 182, .22) !important;
        border-color: rgba(0, 119, 182, .55) !important;
    }

    .mini-info {
        background: rgba(0, 119, 182, .12);
        border: 1px solid rgba(0, 119, 182, .25);
        color: var(--text);
        border-radius: 16px;
        padding: .9rem 1rem;
        min-height: 56px;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .tag-help {
        font-size: .85rem;
        color: var(--muted);
        border: 1px solid rgba(255, 255, 255, .12);
        background: rgba(255, 255, 255, .06);
        padding: .35rem .6rem;
        border-radius: 999px;
    }





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
</style>

<body class="pb-5">

    <!-- Barra de navegacion colores -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <img src="../Img/BarberStudio.png" alt="" width="80">
            <a class="navbar-brand" href="index.php">Mexico Barber Studio</a>
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

</body>

</html>