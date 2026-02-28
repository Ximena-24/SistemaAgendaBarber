<?php
include_once "../conexion.php";
$idServicio = isset($_GET['servicio']) ? $_GET['servicio'] : 0;

// Obtener detalles del servicio seleccionado para mostrar en el encabezado
$sql = "SELECT NombreServicio, TimpoServicio FROM TBL_BarberServicios WHERE IdServ = ?";
$params = array($idServicio);
$stmt = sqlsrv_query($conn, $sql, $params);
$servicioInfo = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reserva tu Cita | La Mexico Barber</title>
    <link href="../Img/icono2.webp" rel="icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-dark: #001D3D;
            --blue-light: #0077B6;
            --red-main: #E63946;
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
    </style>

    <style>
        :root {
            --barber-red: #E63946;
            --barber-blue: #0056b3;
            --barber-white: #ffffff;
        }

        /* NAVBAR CON MOVIMIENTO BARBER POLE */
        .navbar {
            padding: 0;
            /* Para que el fondo llegue a las orillas */
            background: repeating-linear-gradient(-45deg,
                    var(--barber-red) 0%,
                    var(--barber-red) 25%,
                    var(--barber-white) 25%,
                    var(--barber-white) 50%,
                    var(--barber-blue) 50%,
                    var(--barber-blue) 75%);
            background-size: 200% 200%;
            animation: barberFlow 10s linear infinite;
            border-bottom: 3px solid #000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        @keyframes barberFlow {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        /* ESTILO DEL NOMBRE (LOGO) */
        .navbar-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem !important;
            letter-spacing: 2px;
            color: #000 !important;
            /* Negro para que resalte sobre los colores */
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 20px !important;
            transform: skew(-10deg);
            /* Efecto inclinado moderno */
            margin-left: 10px;
        }

        /* ENLACES DEL MENÚ */
        .nav-link {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #000 !important;
            /* Negro para contraste */
            background: rgba(255, 255, 255, 0.6);
            margin: 5px;
            border-radius: 4px;
            transition: 0.3s;
            text-align: center;
        }

        .nav-link:hover {
            background: #000;
            color: #fff !important;
        }

        /* BOTÓN RESPONSIVO (HAMBURGUESA) */
        .navbar-toggler {
            border: 2px solid #000 !important;
            background-color: rgba(255, 255, 255, 0.8) !important;
            margin-right: 10px;
        }

        /* Ajuste para que el menú móvil se vea bien sobre los colores */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.95);
                padding: 20px;
                border-bottom: 5px solid var(--barber-red);
            }
        }
    </style>
</head>

<body>



    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <img src="../Img/LogoBarberMexico.png" alt="" width="100">
            <a class="navbar-brand" href="#">Mexico Barber Studio</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarMenu" aria-controls="navBarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navBarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#servicios">SERVICIOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="#nosotros">NOSOTROS</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="Public/servicios.php" style="background: #000; color: #fff !important;">RESERVAR YA</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>




    <!-- Encabezado antes de calendario y Calendario -->
    <div class="container py-5">
        <div class="text-center">
            <!-- <img src="../Img/BarberStudio.png" alt="Logo" width="150"> -->
            <h2 class="mt-3"><strong> PROGRAMA TU CITA </strong></h2>
            <h5>
                <span style="color: #0077B6;">PROGRAMA TU CITA</span> DE
                <span style="color: #E63946;"><?= $servicioInfo['NombreServicio'] ?></strong> (<?= $servicioInfo['TimpoServicio'] ?>)</span>
            </h5>
            <div class="service-badge">
                LA MEXICO BARBER STUDIO
            </div>
        </div>
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
    </div>
    <!-- Encabezado antes de calendario y Calendario -->


    <!-- Modal de directorio y confirmacion de cliente -->
    <div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white" style="border: 2px solid var(--blue-light);">
                <!-- <img src="../Img/LogoBarberMexico.png" alt="" width="50%"> -->
                <!--  Texto principal de confirmacion -->
                <div class="modal-header border-0 d-flex justify-content-center position-relative">

                    <h3 class="modal-title w-100 text-center">
                        <strong>CONFIRMACION DE CITA</strong>
                    </h3>
                    <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
                </div>
                <!--  Texto principal de confirmacion -->



                <div class="modal-body">
                    <p>Has seleccionado el siguiente horario para tu <strong><?= $servicioInfo['NombreServicio'] ?></strong>:</p>
                    <h4 id="fechaSeleccionada" class="text-info text-center"></h4>

                    <form action="guardar_reserva.php" method="POST" class="mt-4">
                        <input type="hidden" name="idServicio" value="<?= $idServicio ?>">
                        <input type="hidden" name="fecha_hora" id="inputFechaHora">
                        <div class="mb-3">
                            <label class="form-label">Tu Nombre</label>
                            <input type="text" name="cliente" class="form-control bg-secondary text-white border-0" required>
                        </div>
                        <button type="submit" class="btn w-100" style="background: var(--red-main); color: white; font-weight: 800;">CONFIRMAR RESERVA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                slotMinTime: '08:00:00',
                slotMaxTime: '21:00:00',
                allDaySlot: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                // --- FORMATO DE CABECERA (Día y Número) ---
                dayHeaderFormat: {
                    weekday: 'long',
                    day: '2-digit'
                },

                // --- FORMATO DE HORAS (8 AM, 2 PM, etc.) ---
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: true,
                    meridiem: 'short',
                    hour12: true // Forzar formato 12 horas
                },

                // --- VISIBILIDAD DE HORA EN EVENTOS ---
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short',
                    hour12: true
                },

                dateClick: function(info) {
                    const fechaStr = info.date.toLocaleString('es-MX', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric', // Añadido el año
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true // Formato AM/PM en el modal
                    });

                    document.getElementById('fechaSeleccionada').innerText = fechaStr;
                    document.getElementById('inputFechaHora').value = info.dateStr;
                    modalReserva.show();
                },
                events: []
            });

            calendar.render();
        });
    </script>

</body>

</html>