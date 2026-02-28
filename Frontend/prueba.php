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
    <link href="../Img/LogoBarberMexico.png" rel="icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
            height: 250px;
            overflow: hidden;
        }

        .img-wrap::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent, var(--blue-dark));
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
</head>

<body>

    <div class="bg-lines">
        <svg width="100%" height="100%">
            <line x1="0" y1="0" x2="100%" y2="100%" stroke="white" stroke-width="2" />
            <line x1="100%" y1="0" x2="0" y2="100%" stroke="white" stroke-width="2" />
        </svg>
    </div>


    <div class="container py-5 text-center">
        <!-- <h1 class="hero-title mb-2" data-aos="zoom-in">LA MEXICO BARBER STUDIO</h1> -->
        <img src="../Img/BarberStudio.png" alt="" width="19%">
        <h3 class="hero" data-aos="zoom-in">SERVICIOS DE LA MEXICO BARBER STUDIO</h3>

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
                                ¡RESERVAR YA!
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>




    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });

        document.querySelectorAll('.reservar').forEach(btn => {
            btn.onclick = () => {
                btn.style.transform = "scale(0.9)";
                window.location = "reservar.php?servicio=" + btn.dataset.id;
            }
        });
    </script>
</body>

</html>







