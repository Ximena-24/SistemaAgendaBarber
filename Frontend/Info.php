<?php
include_once "../conexion.php";
$sql = "SELECT * FROM TBL_BarberServicios WHERE Status = 1";
$servicios = sqlsrv_query($conn, $sql);

if ($servicios === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informacion Mexico Barber</title>
    <link href="../Img/BarberStudio.png" rel="icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-dark: #001D3D;
            --blue-light: #0077B6;
            --red-main: #E63946;
            --white: #F1FAEE;
            --accent: #CAF0F8;
        }

        body {
            background-color: var(--blue-dark);
            background-image: radial-gradient(circle at 20% 30%, rgba(0, 119, 182, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(230, 57, 70, 0.1) 0%, transparent 40%);
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }

        /* Título Estilo Graffiti/Moderno */
        .hero-title {
            font-family: 'Bebas Neue', cursive;
            font-size: 5rem;
            letter-spacing: 4px;
            color: var(--white);
            text-shadow: 4px 4px var(--red-main), 8px 8px var(--blue-light);
            transform: skew(-5deg);
        }

        /* Contenedor de Tarjetas Dinámicas */
        .card-barber {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 0px;
            clip-path: polygon(0 0, 100% 5%, 100% 100%, 0 95%);
            /* Corte diagonal */
            transition: all 0.4s ease;
            position: relative;
            margin-bottom: 30px;
        }

        .card-barber:hover {
            transform: scale(1.03) rotate(1deg);
            background: rgba(255, 255, 255, 0.1);
        }

        /* Imagen con Filtro de Color */
        .img-wrap {
            position: relative;
            height: 280px;
            overflow: hidden;
        }

        .img-wrap::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background: linear-gradient(to bottom, transparent, var(--blue-dark)); */
        }

        .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(40%) contrast(120%);
        }

        /* Detalles del Servicio */
        .service-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            color: var(--accent);
            margin: 0;
        }

        .price-tag {
            background: var(--red-main);
            color: white;
            padding: 5px 20px;
            font-weight: 800;
            display: inline-block;
            transform: skew(-15deg);
            margin: 10px 0;
        }

        .time-info {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--blue-light);
            font-weight: bold;
        }

        /* Botón de Acción */
        .btn-booking {
            background: var(--blue-light);
            border: none;
            color: white;
            font-weight: 800;
            padding: 15px;
            width: 100%;
            text-transform: uppercase;
            transition: 0.3s;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
        }

        .btn-booking:hover {
            background: var(--red-main);
            color: white;
            box-shadow: 0 0 20px rgba(230, 57, 70, 0.6);
        }

        /* Elementos Decorativos de Fondo (Estilo imagen referencia) */
        .bg-lines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.05;
            pointer-events: none;
        }
    </style>

    <style>
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
    </style>

    <style>
        /* ESTILOS DE NOTICIAS */
        .card-news {
            background: rgba(255, 255, 255, 0.03);
            border-left: 4px solid #BF953F;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .card-news:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(10px);
        }

        .card-news-body {
            padding: 20px;
        }

        .card-news h4 {
            font-family: 'Bebas Neue';
            letter-spacing: 1px;
            color: #BF953F;
        }

        .promo-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--red-main);
            color: white;
            padding: 5px 15px;
            font-size: 12px;
            font-weight: bold;
            transform: rotate(0deg);
            border-bottom-left-radius: 10px;
        }

        /* AJUSTE PARA EL TITULO HERO */
        .hero {
            letter-spacing: 5px;
            text-shadow: 2px 2px 0px #000;
            color: #BF953F;
        }
    </style>

    <style>
        /* Contenedor principal de la ubicación */
        .location-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .location-title {
            font-family: 'Bebas Neue', sans-serif;
            color: #BF953F;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .muted-text {
            color: var(--muted);
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .address-text {
            color: #fff;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        /* Estilo del Mini Mapa */
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(191, 149, 63, 0.3);
            /* Borde dorado tenue */
            margin-bottom: 15px;
            filter: grayscale(0.5) contrast(1.2) invert(0.9) hue-rotate(180deg);
            /* EFECTO MODO OSCURO */
        }

        /* Botón elegante */
        .btn-map {
            display: block;
            text-decoration: none;
            color: #BF953F;
            border: 1px solid #BF953F;
            padding: 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 800;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-map:hover {
            background: #BF953F;
            color: #022a55;
            box-shadow: 0 0 15px rgba(191, 149, 63, 0.4);
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
</head>

<body>

    <a href="javascript:history.back()" class="btn-nav-top-left" title="Regresar">⬅️</a>

    <div class="bg-lines">
        <svg width="100%" height="100%">
            <line x1="0" y1="0" x2="100%" y2="100%" stroke="white" stroke-width="2" />
            <line x1="100%" y1="0" x2="0" y2="100%" stroke="white" stroke-width="2" />
        </svg>
    </div>

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
    <br>


    <div class="row mt-5 g-4">
        <div class="col-lg-8" data-aos="fade-right">
            <div class="d-flex align-items-center mb-4">
                <div style="width: 5px; height: 30px; background: #BF953F; margin-right: 15px;"></div>
                <h1 class="text-uppercase m-0" style="font-family: 'Bebas Neue'; letter-spacing: 2px;">Noticias y Promociones 📢</h1>
            </div>

            <div class="card-news mb-4">
                <div class="promo-badge">¡PROMO DE HOY!</div>
                <div class="card-news-body">
                    <h4>CORTES GRATIS A LOS PRIMEROS 2</h4>
                    <p>Hoy estamos celebrando nuestro aniversario. Ven antes de las 2:00 PM y si eres de los primeros 5, tu corte va por nuestra cuenta. ✂️🔥</p>
                    <small class="text-secondary">Publicado hace 2 horas por: Luis Orlando</small>
                </div>
            </div>

            <div class="card-news">
                <div class="card-news-body">
                    <h4>NUEVO SERVICIO: EXFOLIACIÓN PREMIUM</h4>
                    <p>Hemos añadido el servicio de limpieza profunda con mascarilla negra. ¡Reserva ya para estrenarlo!</p>
                    <small class="text-secondary">Publicado ayer</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4" data-aos="fade-left">
            <div class="card-barber p-4 text-start">
                <div class="location-card">
                    <h4 class="location-title">📍 UBICACIÓN</h4>
                    <p class="muted-text"><strong> Encuéntranos en: </strong></p>
                    <p class="address-text">Calle Romo de Vivar 104B, Centro, 20300 San Francisco de los Romo, Ags.</p>
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3700.000000000000!2d-102.2757188!3d22.0790812!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8429ed9a6666666b%3A0x684b3f1784c6be58!2sLa%20Mexico%20Barber%20Studio!5e0!3m2!1ses!2smx!4v1700000000000!5m2!1ses!2smx"
                            width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                    <a href="https://maps.apple.com/place?address=Calle+Romo+de+Vivar+104B%2C+Centro%2C+20300+San+Francisco+de+los+Romo%2C+Ags.%2C+Mexico&ll=22.0790812%2C-102.2757188&q=La+Mexico+Barber+Studio"
                        target="_blank" class="btn-map">
                        VER EN MAPAS
                    </a>
                    <hr style="color: white;">
                    <h4 style="font-family: 'Bebas Neue'; color: #BF953F;">🕒 HORARIOS</h4>
                    <ul class="list-unstyled small">
                        <li class="d-flex justify-content-between"><span>Lunes - Jueves:</span> <span>10:00am - 7:30pm</span></li>
                        <li class="d-flex justify-content-between"><span>Viernes - Sábados:</span> <span>07:00am - 07:00pm</span></li>
                        <li class="d-flex justify-content-between text-danger"><span>Domingos:</span> <span>CERRADO</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
    </script>
</body>

</html>