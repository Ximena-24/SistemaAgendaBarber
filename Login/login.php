<?php include "../conexion.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Login</title>
    <link href="../Img/LogoBarberMexico.png" rel="icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --gold: #BF953F;
            /* Color dorado real */
            --dark-barber: rgba(255, 218, 54, 0.8);
            --glass: rgba(6, 2, 40, 0.54);
        }

        body {
            background: url('../Img/fondoCorte.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            /* Cambiado a min-height para permitir scroll */
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            /* Espacio para que no toque los bordes en móvil */
        }

        /* Capa de oscuridad sobre el fondo */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(12, 0, 47, 0.58) 0%, rgba(1, 4, 60, 0.65) 100%);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            /* Ocupa el ancho disponible */
            max-width: 450px;
            /* Tamaño máximo elegante en escritorio */
            margin: auto;
        }

        .login-card {
            background: var(--glass);
            border: 1px solid rgba(197, 160, 89, 0.3);
            border-radius: 8px;
            /* Un poco redondeado para modernidad */
            padding: 40px 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            text-align: center;
        }

        /* LOGO DORADO RESPONSIVO */
        .navbar-brand {
            display: inline-block;
            font-family: 'Bebas Neue', 'Impact', sans-serif;
            font-size: clamp(1.5rem, 5vw, 2.2rem) !important;
            /* Ajusta tamaño según pantalla */
            font-weight: 900;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #BF953F 20%, #FCF6BA 45%, #B38728 70%, #AA771C 85%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(2px 2px 0px #000);
            margin-bottom: 10px;
            text-decoration: none;
        }

        .barber-subtitle {
            color: var(--gold);
            font-size: 0.85rem;
            letter-spacing: 2px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Estilos de Formulario */
        .input-group {
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control,
        .form-select {
            background: transparent !important;
            border: none !important;
            color: white !important;
            padding: 12px;
        }

        .form-select option {
            background: #01004f;
            /* Fondo oscuro para que se lea el texto en el select */
            color: white;
        }

        .btn-login {
            width: 100%;
            background: transparent;
            border: 1px solid var(--gold);
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            padding: 14px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: var(--gold);
            color: #000;
            box-shadow: 0 0 15px rgba(191, 149, 63, 0.4);
        }

        /* Media Query para pantallas muy pequeñas */
        @media (max-width: 400px) {
            .login-card {
                padding: 25px 15px;
            }

            .barber-subtitle {
                font-size: 0.7rem;
            }
        }

        /* Esto selecciona el placeholder de todos los inputs con la clase .form-control */
        .form-control::placeholder {
            color: #ffffff !important;
            opacity: 0.8;
            /* Un toque de transparencia para que se vea más profesional */
        }

        /* Soporte para Internet Explorer y Edge antiguo */
        .form-control:-ms-input-placeholder {
            color: #ffffff !important;
        }

        /* Soporte para Firefox antiguo */
        .form-control::-moz-placeholder {
            color: #ffffff !important;
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <div class="login-container fade-in">
        <div class="login-card">
            <div class="logo-area">
                <i class="fas fa-razor"></i>
                <img src="../img/BarberStudio.png" alt="" width="100">
                <!-- <h1 class="barber-title">La Mexico Barber Studio</h1> -->
                <a class="navbar-brand" href="#">La Mexico Barber Studio</a>
                <div class="barber-subtitle">SISTEMA DE GESTIÓN DE BARBER</div>
            </div>

            <form action="Validar.php" method="post" autocomplete="off">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                    <select class="form-select" id="Usua_Log" name="Usua_Log" required style="background-color: #a8a8a85e;">
                        <option value="" hidden>Selecciona tu Usuario</option>
                        <?php
                        $consulta = "SELECT idUser, Usuario FROM TBL_UsuariosBarber WHERE Status = 1 ORDER BY Usuario ASC";
                        $ejecutar = sqlsrv_query($conn, $consulta);
                        if ($ejecutar) {
                            while ($fila = sqlsrv_fetch_array($ejecutar, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value="' . $fila['idUser'] . '">' . htmlspecialchars($fila['Usuario']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="Pass_Log" id="Pass_Log" class="form-control" placeholder="Contraseña">
                </div>

                <button type="submit" class="btn btn-login">Iniciar Sesión</button>
            </form>

            <div class="mt-4">
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalRecuperar"
                    style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.8rem;">
                    ¿Olvidaste tu acceso?
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRecuperar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" style="color: #BF953F;">Restablecer Acceso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="small">Contacta al administrador para restablecer tu contraseña o ingresa tus datos si el módulo está activo.</p>
                    <input type="text" id="rec_user" class="form-control mb-2" placeholder="Usuario">
                    <input type="number" id="rec_tel" class="form-control mb-2" placeholder="Teléfono Registrado">
                    <input type="password" id="new_pass" class="form-control mb-2" placeholder="Nueva Contraseña">
                    <button onclick="restablecerPass()" class="btn btn-login" style="border-color: #BF953F;">Cambiar Contraseña</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // DETECTOR DE ERRORES CON SWEETALERT
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('error')) {
            Swal.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: 'La contraseña es incorrecta.',
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#BF953F'
            });
        }

        if (urlParams.has('vacio')) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos Vacíos',
                text: 'Por favor, completa todos los campos.',
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#BF953F'
            });
        }

        function restablecerPass() {
            // Aquí llamarías a tu recuperar_proceso.php (te ayudo con eso después)
            Swal.fire('Procesando', 'Estamos validando tus datos...', 'info');
        }
    </script>
</body>

</html>