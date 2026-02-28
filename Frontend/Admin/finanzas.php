<?php include_once "../../conexion.php";
session_start();

// Si el usuario intenta entrar a la mala sin loguearse, lo mandamos al login
if (!isset($_SESSION['UserName'])) {
    header("Location: ../../Login/login.php");
    exit();
} ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Finanzas | Admin</title>
    <link href="../../Img/BarberStudio.png" rel="icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --blue-dark: #090632;
            --accent-gold: #BF953F;
            --red-barber: #E63946;
            --glass: rgba(255, 255, 255, .05);
            --glass-border: rgba(255, 255, 255, .10);
            --text: #fff;
            --muted: rgba(255, 255, 255, .65);
        }

        body {
            background: var(--blue-dark);
            background-image:
                radial-gradient(circle at 10% 10%, rgba(191, 149, 63, 0.12), transparent 30%),
                radial-gradient(circle at 85% 70%, rgba(230, 57, 70, 0.10), transparent 35%),
                linear-gradient(rgba(9, 6, 50, .88), rgba(9, 6, 50, .88)),
                url('../../Img/fondoCorte.png');
            background-size: cover;
            background-attachment: fixed;
            color: var(--text);
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }

        .title-barber {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 5px;
            color: var(--accent-gold);
            text-shadow: 2px 2px 0px #000;
            font-size: clamp(2rem, 4vw, 2.4rem);
            margin: 0;
        }

        .muted {
            color: var(--muted);
        }

        /* Cards KPI */
        .stat-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 22px;
            padding: 22px;
            transition: .25s ease;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: rgba(191, 149, 63, .55);
            box-shadow: 0 16px 40px rgba(0, 0, 0, .45);
        }

        .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.55rem;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, .10);
        }

        .bg-gold {
            background: rgba(191, 149, 63, .18);
            color: var(--accent-gold);
        }

        .bg-red {
            background: rgba(230, 57, 70, .18);
            color: var(--red-barber);
        }

        .bg-blue {
            background: rgba(0, 119, 182, .18);
            color: #49b3ff;
        }

        .bg-green {
            background: rgba(16, 185, 129, .18);
            color: #34D399;
        }

        .number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            letter-spacing: 2px;
            line-height: 1.0;
        }

        .sub {
            font-size: .88rem;
            color: rgba(255, 255, 255, .62);
            margin-top: 6px;
        }

        /* Chart containers */
        .chart-container {
            background: var(--glass);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 22px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
            height: 100%;
            overflow: hidden;
        }

        .chart-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .06);
            color: #fff;
            font-weight: 600;
            font-size: .92rem;
            white-space: nowrap;
        }

        /* nav pills */
        .nav-pills .nav-link {
            color: white;
            border-radius: 12px;
            margin: 0 6px;
            font-weight: 700;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .10);
        }

        .nav-pills .nav-link.active {
            background: var(--accent-gold);
            color: #120b2f;
            border-color: rgba(191, 149, 63, .65);
        }

        /* Tabla */
        .table-glass {
            background: rgba(255, 255, 255, .03);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .10);
            overflow: hidden;
        }

        .table thead th {
            color: rgba(6, 0, 0, 0.85);
            border-bottom: 1px solid rgba(255, 255, 255, .10) !important;
        }

        .table tbody td {
            color: rgba(0, 0, 0, 0.88);
            border-color: rgba(255, 255, 255, .08) !important;
        }

        /* inputs */
        .form-select,
        .form-control {
            background: rgba(255, 255, 255, .06) !important;
            border: 1px solid rgba(255, 255, 255, .10) !important;
            color: #fff !important;
            border-radius: 12px !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, .55);
        }

        /* Top 3 */
        .top3-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(255, 255, 255, .04);
            margin-top: 10px;
        }

        .top3-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .medal {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(0, 0, 0, .18);
            font-size: 1.15rem;
        }

        .top3-name {
            font-weight: 800;
        }

        .top3-sub {
            font-size: .85rem;
            color: rgba(255, 255, 255, .60);
        }

        .top3-amt {
            font-weight: 900;
            letter-spacing: .3px;
        }

        canvas {
            max-height: 340px;
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

    <style>
        /* Estilo para que el select se vea premium y no el default de Windows/Chrome */
        .custom-select-dark {
            background-color: #06013ddb !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px !important;
            font-weight: 600;
            cursor: pointer;
        }

        .custom-select-dark:focus {
            border-color: #BF953F !important;
            box-shadow: 0 0 0 0.25rem rgba(191, 149, 63, 0.25) !important;
        }

        /* Asegura que en móviles muy pequeños el contenedor no se rompa */
        .flex-nowrap {
            overflow-x: auto;
            /* Permite scroll horizontal si el celular es muy pequeño */
            scrollbar-width: none;
            /* Oculta scroll en Firefox */
        }

        .flex-nowrap::-webkit-scrollbar {
            display: none;
            /* Oculta scroll en Chrome/Safari */
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
    <br><br><br><br>
    <!-- Barra de navegacion colores -->

    <div class="container-fluid p-4">
        <div class="d-md-flex justify-content-between align-items-center mb-4 mt-3">
            <!-- <div><h4 class="title-barber">FINANZAS Y CONTROL 📈</h4> </div> -->
            <div class="mt-3 mt-md-0 d-flex align-items-center gap-2 flex-nowrap">
                <select id="rangeSelect" class="form-select custom-select-dark" style="width: auto; min-width: 160px;">
                    <option value="this_week">Esta semana</option>
                    <option value="last_15d">Últimos 15 días</option>
                    <option value="last_30d">Últimos 30 días</option>
                    <option value="this_month" selected>Este mes</option>
                    <option value="last_12m">Últimos 12 meses</option>
                </select>

                <button id="btnRefresh" class="btn btn-light d-flex align-items-center" style="border-radius:12px; font-weight:800; white-space: nowrap;">
                    <i class="bi bi-arrow-repeat me-1"></i> <span class="d-none d-sm-inline">Actualizar</span>
                </button>

                <a class="btn btn-outline-light d-flex align-items-center" style="border-radius:12px; font-weight:800; white-space: nowrap;" href="../index.php">
                    <i class="bi bi-house-door me-1"></i> <span class="d-none d-sm-inline"> Ir al Sitio</span>
                </a>
            </div>

        </div>

        <!-- KPIs (con tus ids reales) -->
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="stat-card">
                    <div class="icon-box bg-gold"><i class="bi bi-currency-dollar"></i></div>
                    <div class="text-white-50 small fw-bold">INGRESOS HOY</div>
                    <div class="number" id="k_ing_hoy">$0</div>
                    <div class="sub" id="k_hoy_date">—</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="stat-card">
                    <div class="icon-box bg-blue"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="text-white-50 small fw-bold">INGRESOS SEMANA</div>
                    <div class="number" id="k_ing_sem">$0</div>
                    <div class="sub" id="k_sem_range">—</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="stat-card">
                    <div class="icon-box bg-gold"><i class="bi bi-currency-dollar"></i></div>
                    <div class="text-white-50 small fw-bold">INGRESOS TOTALES</div>
                    <div class="number" id="k_ing_total">$0</div>
                    <div class="sub" id="k_rango_label">—</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="stat-card">
                    <div class="icon-box bg-red"><i class="bi bi-scissors"></i></div>
                    <div class="text-white-50 small fw-bold">SERVICIOS REALIZADOS</div>
                    <div class="number" id="k_servicios">0</div>
                    <div class="sub">Citas completadas</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="stat-card">
                    <div class="icon-box bg-red"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="text-white-50 small fw-bold">CANCELADAS (MES)</div>
                    <div class="number" id="k_cancel">0</div>
                    <div class="sub"><span id="k_cancel_pct">0</span>%</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="stat-card">
                    <div class="icon-box bg-green"><i class="bi bi-receipt"></i></div>
                    <div class="text-white-50 small fw-bold">TICKET PROM (SEMANA)</div>
                    <div class="number" id="k_ticket">$0</div>
                    <div class="sub"><span id="k_citas_sem">0</span> citas</div>
                </div>
            </div>
        </div>

        <!-- Charts + Tabla -->
        <div class="row g-4 mt-1">
            <div class="col-12 col-lg-8">
                <div class="chart-container">
                    <div class="chart-head">
                        <div>
                            <div class="fw-bold">📊 Serie de ingresos</div>
                            <div class="muted" id="serieHint">—</div>
                        </div>
                        <span class="pill" id="topServicioPill">⭐ Top servicio: —</span>
                    </div>
                    <div style="height:320px">
                        <canvas id="chartIngresos"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="chart-container">
                    <div class="chart-head">
                        <div>
                            <div class="fw-bold">🧠 Por servicio</div>
                            <div class="muted" id="servHint">—</div>
                        </div>
                        <i class="bi bi-three-dots-vertical"></i>
                    </div>
                    <div style="height:300px">
                        <canvas id="chartServicios"></canvas>
                    </div>
                    <div class="mt-3" id="top3Servicios"></div>
                </div>
            </div>


            <div class="col-12">
                <div class="chart-container">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div class="fw-bold">✅ Últimas completadas</div>
                        <input id="tableSearch" class="form-control" placeholder="Buscar cliente/servicio..." style="max-width:320px;" />
                    </div>

                    <div class="table-responsive table-glass">
                        <table class="table table-sm align-middle mb-0 table-striped table-bordered table-secondary">
                            <thead class="thead-dark" style="background-color: #090632;" >
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyVentas">
                                <tr>
                                    <td colspan="6" class="muted">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Rutas
        const API_BASE = "../../Backend/Admin/finanzas";
        const API_KPIS = `${API_BASE}/kpis.php`;
        const API_SERIE = `${API_BASE}/serie_ingresos.php`;
        const API_SERV = `${API_BASE}/por_servicio.php`;
        const API_LAST = `${API_BASE}/ultimas_completadas.php`;

        const money = (n) => (Number(n || 0)).toLocaleString("es-MX", {
            style: "currency",
            currency: "MXN"
        });

        const dmy = (ymd) => {
            if (!ymd) return "—";
            const [y, m, d] = ymd.split("-").map(Number);
            const dt = new Date(y, (m || 1) - 1, d || 1);
            return new Intl.DateTimeFormat("es-MX", {
                day: "2-digit",
                month: "long",
                year: "numeric"
            }).format(dt);
        };

        // Charts
        let chartIngresos, chartServicios;

        function paletteLuxury() {
            return [
                "#BF953F", "#E63946", "#49b3ff", "#10B981", "#8B5CF6",
                "#FBBF24", "#EC4899", "#22D3EE", "#34D399", "#FB7185",
                "#60A5FA", "#A78BFA", "#f97316", "#84cc16", "#06b6d4"
            ];
        }

        // ✅ colores únicos por índice (sin repetidos por hash)
        function buildColorsForLabels(labels) {
            const pal = paletteLuxury();
            const uniq = [...new Set(labels.map(x => (x || "").trim()))];
            const map = new Map();
            uniq.forEach((name, i) => map.set(name, pal[i % pal.length]));
            return labels.map(name => map.get((name || "").trim()) || pal[0]);
        }

        function renderTop3Servicios(items) {
            const el = document.getElementById("top3Servicios");
            if (!el) return;

            const arr = [...(items || [])]
                .map(x => ({
                    servicio: x.servicio ?? x.Servicio ?? x.NombreServicio ?? x.nombreServicio ?? "Servicio",
                    total: Number(x.total ?? x.Total ?? x.total_ingresos ?? 0),
                    count: Number(x.count ?? x.Count ?? x.citas ?? x.Citas ?? 0)
                }))
                .sort((a, b) => b.total - a.total)
                .slice(0, 3);

            if (!arr.length) {
                el.innerHTML = `<div class="muted">Sin datos para Top 3.</div>`;
                return;
            }

            const icons = ["🥇", "🥈", "🥉"];
            el.innerHTML = arr.map((x, i) => `
      <div class="top3-item">
        <div class="top3-left">
          <div class="medal">${icons[i]}</div>
          <div>
            <div class="top3-name">${x.servicio}</div>
            <div class="top3-sub">${x.count ? `${x.count} citas` : ""}</div>
          </div>
        </div>
        <div class="top3-amt">${money(x.total)}</div>
      </div>
    `).join("");
        }

        function ensureCharts() {
            if (!chartIngresos) {
                chartIngresos = new Chart(document.getElementById("chartIngresos"), {
                    type: "line",
                    data: {
                        labels: [],
                        datasets: [{
                            label: "Ingresos",
                            data: [],
                            borderColor: "#BF953F",
                            backgroundColor: "rgba(191,149,63,.12)",
                            borderWidth: 3,
                            fill: true,
                            tension: .40,
                            pointRadius: 3,
                            pointBackgroundColor: "#ffffff",
                            pointBorderColor: "#BF953F"
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                grid: {
                                    color: "rgba(255,255,255,.06)"
                                },
                                ticks: {
                                    color: "rgba(255,255,255,.70)",
                                    callback: (v) => money(v)
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: "rgba(255,255,255,.65)"
                                }
                            }
                        }
                    }
                });
            }

            if (!chartServicios) {
                chartServicios = new Chart(document.getElementById("chartServicios"), {
                    type: "doughnut",
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            borderWidth: 2,
                            borderColor: "#ffffff",
                            hoverOffset: 16
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "top",
                                labels: {
                                    color: "#fff",
                                    padding: 16,
                                    boxWidth: 34
                                }
                            }
                        }
                    }
                });
            }
        }

        function calcRange() {
            const v = document.getElementById("rangeSelect").value;
            const now = new Date();
            const ymd = (dt) => dt.toISOString().slice(0, 10);

            if (v === "this_week") {
                const d = new Date(now);
                const day = d.getDay();
                const diffToMon = (day === 0) ? 6 : (day - 1);
                d.setDate(d.getDate() - diffToMon);
                const from = ymd(d);
                const to = new Date(d);
                to.setDate(to.getDate() + 7);
                return {
                    from,
                    to: ymd(to),
                    hint: "Semana actual"
                };
            }

            if (v === "this_month") {
                const from = new Date(now.getFullYear(), now.getMonth(), 1);
                const to = new Date(now.getFullYear(), now.getMonth() + 1, 1);
                return {
                    from: ymd(from),
                    to: ymd(to),
                    hint: "Mes actual"
                };
            }

            if (v === "last_12m") {
                const from = new Date(now.getFullYear(), now.getMonth() - 11, 1);
                const to = new Date(now.getFullYear(), now.getMonth() + 1, 1);
                return {
                    from: ymd(from),
                    to: ymd(to),
                    hint: "Últimos 12 meses"
                };
            }

            if (v === "last_15d") {
                const from = new Date(now);
                from.setDate(from.getDate() - 14);
                const to = new Date(now);
                to.setDate(to.getDate() + 1);
                return {
                    from: ymd(from),
                    to: ymd(to),
                    hint: "Últimos 15 días"
                };
            }

            // last_30d
            const from = new Date(now);
            from.setDate(from.getDate() - 29);
            const to = new Date(now);
            to.setDate(to.getDate() + 1);
            return {
                from: ymd(from),
                to: ymd(to),
                hint: "Últimos 30 días"
            };
        }

        async function loadKPIs() {
            const res = await fetch(API_KPIS, {
                cache: "no-store"
            });
            const json = await res.json();
            if (!json.ok) throw new Error(json.msg || "KPIs no disponibles");

            document.getElementById("k_ing_hoy").textContent = money(json.ingreso_hoy);
            document.getElementById("k_ing_sem").textContent = money(json.ingreso_semana);
            document.getElementById("k_ticket").textContent = money(json.ticket_prom_semana);
            document.getElementById("k_citas_sem").textContent = json.citas_semana ?? 0;
            document.getElementById("k_cancel").textContent = json.canceladas_mes ?? 0;
            document.getElementById("k_cancel_pct").textContent = json.porcentaje_canceladas_mes ?? 0;

            document.getElementById("k_hoy_date").textContent = dmy(json.rangos?.today);
            document.getElementById("k_sem_range").textContent =
                `${dmy(json.rangos?.week_start)} - ${dmy((json.rangos?.week_end_exclusive || "").slice(0,10))}`;

            const top = json.top_servicio_mes ? `${json.top_servicio_mes} (${money(json.top_servicio_mes_total)})` : "—";
            document.getElementById("topServicioPill").textContent = `⭐ Top servicio: ${top}`;
        }

        async function loadTotalesYServicios() {
            const r = calcRange();

            // etiqueta del rango en tu card
            const elRango = document.getElementById("k_rango_label");
            if (elRango) elRango.textContent = `${r.hint}: ${dmy(r.from)} - ${dmy(r.to)}`;

            const res = await fetch(`${API_SERV}?from=${encodeURIComponent(r.from)}&to=${encodeURIComponent(r.to)}`, {
                cache: "no-store"
            });

            const json = await res.json();
            if (!json.ok) throw new Error(json.msg || "Servicios no disponibles");

            const items = json.items || [];

            // total ingresos = suma de total por servicio
            const totalIngresos = items.reduce((acc, x) => {
                const v = Number(x.total ?? x.Total ?? x.total_ingresos ?? 0);
                return acc + (isNaN(v) ? 0 : v);
            }, 0);

            // servicios realizados = suma de counts (si existe)
            const totalServicios = items.reduce((acc, x) => {
                const c = Number(x.count ?? x.Count ?? x.citas ?? x.Citas ?? 0);
                return acc + (isNaN(c) ? 0 : c);
            }, 0);

            // pinta cards
            const elIngTotal = document.getElementById("k_ing_total");
            const elServ = document.getElementById("k_servicios");

            if (elIngTotal) elIngTotal.textContent = money(totalIngresos);

            // fallback si tu API no manda count/citas
            if (elServ) elServ.textContent = (totalServicios > 0) ? totalServicios : (items.length || 0);
        }


        async function loadSerie() {
            const v = document.getElementById("rangeSelect").value;
            let range = "30d";
            if (v === "last_12m") range = "12m";
            if (v === "this_week") range = "7d"; // si tu backend lo soporta
            if (v === "this_month") range = "30d"; // o "this_month" si lo soporta


            const res = await fetch(`${API_SERIE}?range=${encodeURIComponent(range)}`, {
                cache: "no-store"
            });
            const json = await res.json();
            if (!json.ok) throw new Error(json.msg || "Serie no disponible");

            const labels = (json.items || []).map(x => x.label);
            const data = (json.items || []).map(x => Number(x.total || 0));

            ensureCharts();
            chartIngresos.data.labels = labels;
            chartIngresos.data.datasets[0].data = data;
            chartIngresos.update();

            document.getElementById("serieHint").textContent =
                range === "12m" ? "Ingresos por mes (12 meses)" : "Ingresos por día (últimos 30 días)";
        }

        async function loadServicios() {
            const r = calcRange();
            document.getElementById("servHint").textContent = `${r.hint}: ${dmy(r.from)} - ${dmy(r.to)}`;

            const res = await fetch(`${API_SERV}?from=${encodeURIComponent(r.from)}&to=${encodeURIComponent(r.to)}`, {
                cache: "no-store"
            });
            const json = await res.json();
            if (!json.ok) throw new Error(json.msg || "Servicios no disponibles");

            const items = json.items || [];
            const labels = items.map(x => x.servicio ?? x.Servicio ?? x.NombreServicio ?? x.nombreServicio ?? "Servicio");
            const data = items.map(x => Number(x.total ?? x.Total ?? x.total_ingresos ?? 0));

            ensureCharts();

            const colors = buildColorsForLabels(labels);

            chartServicios.data.labels = labels;
            chartServicios.data.datasets[0].data = data;
            chartServicios.data.datasets[0].backgroundColor = colors;
            chartServicios.update();

            renderTop3Servicios(items);
        }

        let ventasCache = [];

        function renderVentasTable(filter = "") {
            const q = (filter || "").toLowerCase().trim();
            const rows = q ? ventasCache.filter(x => (`${x.cliente} ${x.servicio}`.toLowerCase()).includes(q)) : ventasCache;

            const tb = document.getElementById("tbodyVentas");
            if (!rows.length) {
                tb.innerHTML = `<tr><td colspan="6" class="muted">Sin resultados.</td></tr>`;
                return;
            }

            tb.innerHTML = rows.map(x => `
      <tr>
        <td>#${x.idCita}</td>
        <td>${x.cliente}</td>
        <td>${x.servicio}</td>
        <td>${dmy(x.fecha)}</td>
        <td>${x.hora}</td>
        <td class="text-end">${money(x.total)}</td>
      </tr>
    `).join("");
        }

        async function loadUltimas() {
            const res = await fetch(`${API_LAST}?top=30`, {
                cache: "no-store"
            });
            const json = await res.json();
            if (!json.ok) throw new Error(json.msg || "Tabla no disponible");

            ventasCache = json.items || [];
            renderVentasTable(document.getElementById("tableSearch").value);
        }

        async function refreshAll() {
            document.getElementById("btnRefresh").disabled = true;
            try {
                await loadKPIs(); // KPIs viejos
                await loadTotalesYServicios(); // ✅ KPIs nuevos (totales por rango)
                await loadSerie(); // chart línea
                await loadServicios(); // doughnut + top3
                await loadUltimas(); // tabla
            } catch (e) {
                console.error(e);
                alert("Error: " + e.message);
            } finally {
                document.getElementById("btnRefresh").disabled = false;
            }
        }


        // Eventos UI
        document.getElementById("btnRefresh").addEventListener("click", refreshAll);

        document.getElementById("rangeSelect").addEventListener("change", async () => {
            await loadSerie();
            await loadServicios();
        });

        document.getElementById("tableSearch").addEventListener("input", (e) => {
            renderVentasTable(e.target.value);
        });


        document.getElementById("rangeSelect").addEventListener("change", () => {
            refreshAll();
        });



        // Nav pills -> sincroniza selector
        document.querySelectorAll("#timeRange .nav-link").forEach(a => {
            a.addEventListener("click", (ev) => {
                ev.preventDefault();
                document.querySelectorAll("#timeRange .nav-link").forEach(x => x.classList.remove("active"));
                a.classList.add("active");

                const r = a.getAttribute("data-range");
                if (r) {
                    document.getElementById("rangeSelect").value = r;
                    refreshAll();
                }
            });
        });

        refreshAll();
    </script>

</body>

</html>