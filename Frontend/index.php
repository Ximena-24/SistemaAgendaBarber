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
    <title>LA MEXICO BARBER</title>
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
    <br><br><br>
    <!-- Barra de navegacion colores -->


    <div class="container py-5 text-center">
        <!-- <h1 class="hero-title mb-2" data-aos="zoom-in">LA MEXICO BARBER STUDIO</h1> -->
        <img src="../Img/BarberStudio.png" alt="" width="19%">
        <h3 class="hero" data-aos="zoom-in"><strong> SERVICIOS DE LA MEXICO BARBER STUDIO </strong></h3>

        <div class="row g-5">
            <?php while ($s = sqlsrv_fetch_array($servicios, SQLSRV_FETCH_ASSOC)):
                // 1. Definimos la ruta de búsqueda
                $id = $s['IdServ'];
                $basePath = "../Img/";
                $extensiones = ['png', 'jpg', 'webp'];
                $imagenPath = $basePath . "default.png"; // Imagen por defecto inicial

                // 2. Buscamos si existe el archivo con el ID del servicio
                foreach ($extensiones as $ext) {
                    $rutaReal = $basePath . $id . "." . $ext;
                    if (file_exists($rutaReal)) {
                        $imagenPath = $rutaReal;
                        break;
                    }
                }

                // 3. Limpiamos la ruta para que el HTML la entienda correctamente
                // Esto cambia "../Img/1.png" a "Img/1.png"
                $imagenHTML = str_replace("../", "", $imagenPath);
            ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="card card-barber">
                        <div class="img-wrap">
                            <img src="../<?= $imagenHTML ?>" alt="<?= $s['NombreServicio'] ?>">
                        </div>
                        <div class="card-body p-4">
                            <p class="time-info mb-1">⏱ <?= $s['TimpoServicio'] ?></p>
                            <h3 class="service-name"><?= $s['NombreServicio'] ?></h3>
                            <div class="price-tag">$<?= number_format($s['CostoServicio'], 0) ?></div>

                            <button class="btn btn-booking mt-3 reservar" data-id="<?= $s['IdServ'] ?>">
                                ¡RESERVAR!
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });

        document.querySelectorAll('.reservar').forEach(btn => {
            btn.onclick = () => {
                btn.style.transform = "scale(0.9)";
                window.location = "reservar_booksy.php?servicio=" + btn.dataset.id;
            }
        });
    </script>
</body>

</html>