<?php include_once "../../conexion.php";
session_start();

// Si el usuario intenta entrar a la mala sin loguearse, lo mandamos al login
if (!isset($_SESSION['UserName'])) {
    header("Location: ../../Login/login.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Corte Mexico Barber</title>
    <link href="../../Img/BarberStudio.png" rel="icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-dark: #090632;
            --blue-light: #0077B6;
            --red-main: #E63946;
            --white: #F1FAEE;
            --accent: #CAF0F8;
        }

        body {
            /* Definimos el color de fondo base */
            background-color: var(--blue-dark);

            /* Capa 1: Los gradientes de luz que ya tenías */
            /* Capa 2: Un overlay oscuro (negro con 70% de transparencia) */
            /* Capa 3: La imagen de fondo */
            background-image:
                radial-gradient(circle at 20% 30%, rgba(0, 119, 182, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(230, 57, 70, 0.1) 0%, transparent 40%),
                linear-gradient(rgba(0, 29, 61, 0.7), rgba(0, 29, 61, 0.7)),
                /* Filtro oscuro */
                url('../../Img/fondoCorte.png');
            /* <--- AQUÍ PONES TU RUTA */

            /* Ajustes para que la imagen se vea perfecta */
            background-attachment: fixed;
            /* La imagen no se mueve al hacer scroll */
            background-position: center;
            /* Centrada */
            background-repeat: no-repeat;
            /* No se repite */
            background-size: cover;
            /* Cubre toda la pantalla */

            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
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

        /* Dropdown de Perfil */
        .profile-link {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            background: #ffffff;
        }
    </style>

    <style>
        :root {
            --blue-dark: #090632;
            --blue-light: #0077B6;
            --red-main: #E63946;
            --white: #F1FAEE;
            --accent: #f9d489;
            /* Dorado */
            --glass: rgba(255, 255, 255, 0.05);
        }

        body {
            background-color: var(--blue-dark);
            background-image:
                radial-gradient(circle at 20% 30%, rgba(0, 119, 182, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(230, 57, 70, 0.1) 0%, transparent 40%),
                linear-gradient(rgba(0, 29, 61, 0.75), rgba(0, 29, 61, 0.75)),
                url('../../Img/fondoCorte.png');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
        }

        /* Navbar Mejorado */
        .navbar {
            background: rgba(0, 22, 56, 0.9) !important;
            backdrop-filter: blur(15px);
            border-bottom: 2px solid var(--accent);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .navbar-brand {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.8rem !important;
            background: linear-gradient(to bottom, #BF953F, #FCF6BA, #AA771C);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 2px;
        }

        /* --- CONTENEDOR MODERNIZADO (GLASSMORPHISM) --- */
        .premium-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.125);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
            transition: transform 0.3s ease;
            margin-top: 2rem;
        }

        .modern-title {
            font-family: 'Bebas Neue', sans-serif;
            color: var(--accent);
            letter-spacing: 3px;
            position: relative;
            margin-bottom: 30px;
        }

        .modern-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--red-main);
        }

        /* Estilización de Inputs */
        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--white);
            margin-bottom: 8px;
            display: block;
        }

        .custom-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(191, 149, 63, 0.3) !important;
            border-radius: 12px !important;
            color: white !important;
            padding: 12px 15px !important;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: var(--accent) !important;
            box-shadow: 0 0 15px rgba(191, 149, 63, 0.2);
            outline: none;
        }

        /* Botón Publicar */
        .btn-modern-action {
            background: linear-gradient(45deg, #BF953F, #AA771C);
            color: #04003ead;
            font-weight: 800;
            text-transform: uppercase;
            border: none;
            border-radius: 12px;
            padding: 15px;
            letter-spacing: 1px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-modern-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(191, 149, 63, 0.4);
            filter: brightness(1.1);
            color: #000;
        }

        /* Responsividad para Móviles */
        @media (max-width: 576px) {
            .premium-card {
                padding: 1.5rem !important;
                margin: 10px;
            }

            .modern-title {
                font-size: 1.7rem;
            }

            .navbar-brand {
                font-size: 1.4rem !important;
            }
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
</head>

<body>
    <a href="javascript:history.back()" class="btn-nav-top-left" title="Regresar">⬅️</a>
    <!-- Barra de navegacion colores -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <img src="../../Img/BarberStudio.png" alt="" width="80">
            <!-- <a class="navbar-brand" href="panel.php">Mexico Barber Studio</a> -->
            <a class="navbar-brand d-none d-lg-inline-block" href="index.php">Mexico Barber Studio</a>
            <div class="d-flex align-items-center order-lg-last">
                <div class="dropdown me-2 me-lg-0">
                    <a href="#" class="profile-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../../Img/icono2.webp" alt="User" class="rounded-circle profile-img" width="35" height="35">
                        <span class="text-white d-none d-md-inline ms-1">Administrador</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg animate slideIn">
                        <li class="dropdown-header text-uppercase small fw-bold">Gestión</li>
                        <li><a class="dropdown-item" href="../Servicios.php">✂️ Cortes</a></li>
                        <li><a class="dropdown-item" href="../Info.php">📍 Nosotros</a></li>
                        <li><a class="dropdown-item" href="../index.php">💈 Servicios</a></li>
                        <li><a class="dropdown-item" href="panel.php">🗓️<strong> Agenda</strong></a></li>
                        <li><a class="dropdown-item" href="finanzas.php">🏠<strong> Panel Admin</strong></a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger fw-bold" href="../../Login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navBarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navBarMenu">
                <ul class="navbar-nav ms-auto me-lg-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="http://localhost:8080/Agenda_Barber/Frontend/Admin/Galeria.php">✂️SUBIR CORTE</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Barra de navegacion colores -->

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding-top: 100px; padding-bottom: 50px;">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                <div class="premium-card p-4 p-md-5">
                    <div class="text-center ">
                        <i class="bi bi-camera-fill text-accent" style="font-size: 2.5rem; color: var(--accent);"></i>
                        <h1 class="modern-title mt-2">NUEVO CORTE</h1>
                        <p class="text-white-50 small mb-4">Actualiza tu galería para que los clientes vean tu trabajo.</p>
                    </div>

                    <form action="guardar_corte.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-type me-2"></i>Nombre del Estilo</label>
                            <input type="text" name="nombreCorte" class="form-control custom-input"
                                placeholder="Ej: High Fade + Beard" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-image me-2"></i>Fotografía del Trabajo</label>
                            <input type="file" name="fotoCorte" class="form-control custom-input"
                                accept="image/*" required>
                            <div class="form-text text-white-50" style="font-size: 0.75rem;">
                                Formatos sugeridos: JPG, PNG o WEBP.
                            </div>
                        </div>

                        <button type="submit" class="btn-modern-action w-100">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> PUBLICAR TRABAJO
                        </button>
                    </form>
                </div>
                <div class="text-center mt-4">
                    <a href="Galeria.php" class="text-white-50 text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Volver a la Galería
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
    </script>
</body>

</html>