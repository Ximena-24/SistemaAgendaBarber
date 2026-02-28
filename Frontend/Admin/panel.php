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
    <title>Panel Administrador</title>
    <link href="../../Img/BarberStudio.png" rel="icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="../../CSS/StylePanel.css" rel="stylesheet">
    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
</head>

<body>
    <div class="bg-lines">
        <svg width="100%" height="100%">
            <line x1="0" y1="0" x2="100%" y2="100%" stroke="white" stroke-width="2" />
            <line x1="100%" y1="0" x2="0" y2="100%" stroke="white" stroke-width="2" />
        </svg>
    </div>

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

    <!-- <section class="gallery-premium-section py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-down">
                <h4 class="modern-title">Panel de Administrador</h4>
                <div class="title-underline"></div>
            </div>
            <br>
            <div class="topbar" style="display:flex;justify-content:space-between;align-items:center;gap:16px;">
                <h2>Panel Administrador</h2>

                <div style="position:relative;">
                    <button id="btnBell" style="font-size:22px; background:transparent; border:0; cursor:pointer;">
                        🔔
                        <span id="bellCount" style="position:absolute;top:-6px;right:-6px;background:#E63946;color:#fff;border-radius:999px;padding:2px 6px;font-size:12px;display:none;">0</span>
                    </button>

                    <div id="bellDropdown" style="display:none; position:absolute; right:0; width:320px; background:#111; color:#fff; border-radius:12px; padding:10px; box-shadow:0 8px 24px rgba(0,0,0,.3); z-index:50;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <b>Notificaciones</b>
                            <button id="btnMarkRead" style="background:#0077B6;color:#fff;border:0;border-radius:10px;padding:6px 10px;cursor:pointer;">Marcar leídas</button>
                        </div>
                        <div id="bellList" style="margin-top:10px; max-height:320px; overflow:auto;"></div>
                    </div>
                </div>
            </div>

            <hr style="opacity:.2">
            <div id="calendar"></div>
            <script src="assets/admin.js"></script>
        </div>
    </section> -->
    <!-- ------------------------- -->

    <!-- CODIGO DE CALENDARIO MODERNO -->
    <div class="container-fluid py-4">
        <!-- TOPBAR -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <div class="pill">
                        <span style="color:var(--accent)">●</span>
                        <strong><?php echo isset($_SESSION['Roll']) && $_SESSION['Roll'] == 1 ? 'Admin:' : 'Barber:'; ?></strong>
                        <?php echo htmlspecialchars($_SESSION['UserName']); ?>
                    </div>
                    <!-- <div class="pill">Agenda | <b>La Mexico Barber Studio</b></div> -->
                </div>
                <!-- <div class="mt-2 muted">Calendario de citas.</div> -->
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Campana (por ahora UI; el backend de notifs lo hacemos en el paso 4) -->
                <button id="btnBell" class="btn btn-soft position-relative">
                    🔔
                    <span id="bellCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="display:none;">0
                    </span>
                </button>
                <a class="btn btn-soft" href="../index.php">Ir al sitio</a>
            </div>
        </div>

        <div class="row g-3">
            <!-- LEFT: CALENDAR -->
            <div class="col-12 col-xl-8">
                <div class="card-pro">
                    <div class="p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <select id="filtroEstatus" class="form-select" style="max-width:190px; color: #ffffff; background-color: #001D3D;">
                                <option value="">Todos los estatus</option>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="COMPLETADA">COMPLETADA</option>
                                <option value="CANCELADA">CANCELADA</option>
                            </select>
                            <input id="filtroTexto" class="form-control"
                                style="width: auto; min-width: 200px; color: #05155d; background-color: #ffffff;"
                                placeholder="Buscar cliente/servicio...">
                            <button class="btn btn-soft" id="btnReset" style="background-color: #001D3D;">Limpiar</button>
                        </div>
                        <!-- Filtros -->
                        <div class="d-flex gap-2 flex-wrap align-items-center"></div>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- RIGHT: PANEL DEL DÍA -->
            <div class="col-12 col-xl-4">
                <div class="card-pro p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="pill">🗓️ Citas del día</div>
                            <div class="muted mt-2" id="lblDia">Selecciona un día en el calendario</div>
                        </div>
                        <button class="btn btn-soft" id="btnHoy">Hoy</button>
                    </div>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <span class="badge-status badge-pend">⚠️ PENDIENTE</span>
                        <span class="badge-status badge-comp">✅ COMPLETADA</span>
                        <span class="badge-status badge-canc">❌ CANCELADA</span>
                    </div>
                    <hr style="color:#F7C163">
                    <div class="mt-3" id="listaDia" style="display:flex;flex-direction:column;gap:10px;">
                        <div class="muted">Aún no hay selección.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DETALLE CITA -->
    <div class="modal fade" id="modalCita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="mTitulo">Detalle de cita</h5>
                        <div class="muted" id="mSub">—</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="card-pro p-3">
                                <div class="pill mb-2">👤 Cliente</div>
                                <div class="fs-5 fw-bold" id="mCliente">—</div>
                                <div class="muted mt-1" id="mTelefono">—</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card-pro p-3">
                                <div class="pill mb-2">⏰ Horario</div>
                                <div class="fs-5 fw-bold" id="mHorario">—</div>
                                <div class="muted mt-1" id="mFecha">—</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card-pro p-3">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <div class="pill mb-2">✂️ Servicio</div>
                                        <div class="fs-5 fw-bold" id="mServicio">—</div>
                                        <div class="muted mt-1" id="mCostoTiempo">—</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="pill">Estatus</div>
                                        <div class="fs-5 fw-bold mt-2" id="mEstatus">—</div>
                                    </div>
                                </div>
                                <hr hidden style="border-color:var(--border);">
                                <div hidden class="pill mb-2">📝 Comentarios</div>
                                <div hidden class="muted" id="mComentarios">—</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <button class="btn btn-soft" id="btnCopiar" hidden>Copiar detalle</button>
                                <button class="btn btn-soft" id="btnPendiente" style="background:#F7C163;color:#fff;border-radius:12px;border:0;">📌 Pendiente</button>
                                <button class="btn" id="btnCompletar" style="background:#2A9D8F;color:#fff;border-radius:12px;border:0;">✅ Completar</button>
                                <button class="btn" id="btnCancelar" style="background:#E63946;color:#fff;border-radius:12px;border:0;">❌ Cancelar</button>
                                <button class="btn btn-soft" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 (asegúrate que esté ANTES de este script) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        // ✅ OJO: tu carpeta es Backend/Admin (A mayúscula)
        const API_CITAS = "../../Backend/Admin/citas_listar.php";
        const API_REPROG = "../../Backend/Admin/citas_actualizar_fecha.php";
        const API_ESTADO = "../../Backend/Admin/citas_cambiar_estado.php";

        let calendar;
        let allEventsCache = [];
        let selectedDate = null;

        const modal = new bootstrap.Modal(document.getElementById('modalCita'));

        function normalizarTelefono(raw) {
            if (!raw) return "";
            // elimina TODO menos números
            return raw.toString().replace(/\D/g, "");
        }

        function toYMD(dateObj) {
            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dateObj.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function formatMoney(v) {
            const n = Number(v ?? 0);
            return n.toLocaleString('es-MX', {
                style: 'currency',
                currency: 'MXN'
            });
        }

        function safe(v) {
            return (v === null || v === undefined || v === '') ? '—' : String(v);
        }

        function iconByStatus(st) {
            st = (st || '').toUpperCase();
            if (st === 'COMPLETADA') return '✅';
            if (st === 'CANCELADA') return '❌';
            return '⚠️';
        }

        function badgeClassByStatus(st) {
            st = (st || '').toUpperCase();
            if (st === 'COMPLETADA') return 'badge-comp';
            if (st === 'CANCELADA') return 'badge-canc';
            return 'badge-pend';
        }

        function applyFilters(events) {
            const est = document.getElementById('filtroEstatus').value.trim();
            const q = document.getElementById('filtroTexto').value.trim().toLowerCase();

            return events.filter(ev => {
                const p = ev.extendedProps || {};
                const st = (p.EstatusAdmin || '').toUpperCase();
                if (est && st !== est) return false;
                if (q) {
                    const hay = [
                        ev.title,
                        p.AliasCliente,
                        p.TelefonoCliente,
                        p.NombreServicio
                    ].join(' ').toLowerCase();
                    if (!hay.includes(q)) return false;
                }
                return true;
            });
        }

        function normalizeStatus(st) {
            st = (st || '').toString().trim().toUpperCase();
            return st || 'PENDIENTE';
        }

        function iconByStatus(st) {
            st = normalizeStatus(st);
            if (st === 'COMPLETADA') return '✅';
            if (st === 'CANCELADA') return '❌';
            return '⚠️';
        }

        function badgeClassByStatus(st) {
            st = normalizeStatus(st);
            if (st === 'COMPLETADA') return 'badge-comp';
            if (st === 'CANCELADA') return 'badge-canc';
            return 'badge-pend';
        }

        // ✅ En vez de successCallback + refreshCalendarEvents (que duplica),
        // pintamos SOLO desde cache con esta función.
        function paintFromCache() {
            const filtered = applyFilters(allEventsCache);

            calendar.batchRendering(() => {
                calendar.removeAllEvents();

                filtered.forEach(e => {
                    const p = e.extendedProps || {};
                    const st = normalizeStatus(p.EstatusAdmin);
                    const ico = iconByStatus(st);

                    // ✅ NO modificamos allEventsCache. Creamos copia para el calendario:
                    const evObj = {
                        ...e,
                        title: `${ico} ${e.title || ''}`.trim(),
                        // opcional: si quieres que el color cambie según estatus
                        color: st === 'COMPLETADA' ? '#2A9D8F' : (st === 'CANCELADA' ? '#E63946' : '#E9C46A')
                    };

                    calendar.addEvent(evObj);
                });
            });
            if (selectedDate) renderListForDay(selectedDate);
        }

        function formatTime12(hhmmss) {
            if (!hhmmss) return "—";
            const parts = hhmmss.toString().split(":");
            const h = parseInt(parts[0] || "0", 10);
            const m = parseInt(parts[1] || "0", 10);
            const d = new Date();
            d.setHours(h, m, 0, 0);

            return new Intl.DateTimeFormat("es-MX", {
                hour: "numeric",
                minute: "2-digit",
                hour12: true
            }).format(d);
        }

        function formatDateDMY(ymd) {
            if (!ymd || ymd === "—") return "—";
            const [y, m, d] = ymd.split("-").map(n => parseInt(n, 10));
            const dt = new Date(y, (m || 1) - 1, d || 1);

            return new Intl.DateTimeFormat("es-MX", {
                day: "2-digit",
                month: "long",
                year: "numeric"
            }).format(dt);
        }

        function renderListForDay(ymd) {
            selectedDate = ymd;
            const fechaBonita = formatDateDMY(ymd);
            document.getElementById('lblDia').textContent = `Citas para ${fechaBonita}`;
            const list = document.getElementById('listaDia');

            const filtered = applyFilters(allEventsCache)
                .filter(e => {
                    const start = e.start || e.startStr;
                    const date = (start instanceof Date) ? toYMD(start) : String(start).substring(0, 10);
                    return date === ymd;
                })
                .sort((a, b) => new Date(a.start) - new Date(b.start));
            if (!filtered.length) {
                list.innerHTML = `<div class="muted">No hay citas para este día con los filtros actuales.</div>`;
                return;
            }

            list.innerHTML = filtered.map(e => {
                const p = e.extendedProps || {};
                const st = normalizeStatus(p.EstatusAdmin);
                const ico = iconByStatus(st);
                const badgeClass = badgeClassByStatus(st);
                const hi12 = formatTime12(p.HoraInicio);
                const hf12 = formatTime12(p.HoraFin);
                return `
      <div class="list-item" role="button" onclick="openById('${e.id}')">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div>
            <div class="fw-bold">${ico} ${safe(p.AliasCliente)} • ${safe(p.NombreServicio)}</div>
            <div class="muted" style="font-size:.92rem;">${hi12} - ${hf12} • ${formatMoney(p.CostoServicio)}</div>
          </div>
          <span class="badge-status ${badgeClass}">${ico} ${st}</span>
        </div>
      </div>
    `;
            }).join('');
        }

        function openById(id) {
            const ev = calendar.getEventById(String(id));
            if (ev) openModal(ev);
        }

        function openModal(event) {
            const p = event.extendedProps || {};
            const fechaYMD = safe(p.Fecha);
            const fechaBonita = formatDateDMY(fechaYMD);
            const hi12 = formatTime12(p.HoraInicio);
            const hf12 = formatTime12(p.HoraFin);

            document.getElementById('mTitulo').textContent = `Cita #${safe(p.IdCita)}`;
            document.getElementById('mSub').textContent = `${fechaBonita} • ${hi12} - ${hf12}`;
            document.getElementById('mCliente').textContent = safe(p.AliasCliente);
            document.getElementById('mTelefono').textContent = `📞 ${safe(p.TelefonoCliente)}`;
            document.getElementById('mHorario').textContent = `${hi12} - ${hf12}`;
            document.getElementById('mFecha').textContent = `📅 ${fechaBonita}`;
            document.getElementById('mServicio').textContent = safe(p.NombreServicio);
            document.getElementById('mCostoTiempo').textContent =
                `⏱ ${safe(p.TiempoServicio)} • 💰 ${formatMoney(p.CostoServicio)}`;
            const st = normalizeStatus(p.EstatusAdmin);
            document.getElementById('mEstatus').textContent = `${iconByStatus(st)} ${st}`;

            const c = (p.Comentarios && String(p.Comentarios).trim() !== '') ? p.Comentarios : 'Sin comentarios.';
            document.getElementById('mComentarios').textContent = c;
            document.getElementById('btnCopiar').onclick = async () => {
                const txt =
                    `Cita #${safe(p.IdCita)}
Cliente: ${safe(p.AliasCliente)}
Tel: ${safe(p.TelefonoCliente)}
Fecha: ${fechaBonita}
Hora: ${hi12} - ${hf12}
Servicio: ${safe(p.NombreServicio)}
Costo: ${formatMoney(p.CostoServicio)}
Estatus: ${iconByStatus(st)} ${st}
Comentarios: ${c}`;
                try {
                    await navigator.clipboard.writeText(txt);
                    Swal.fire('Copiado', 'Detalle copiado al portapapeles', 'success');
                } catch (e) {
                    Swal.fire('Ups', 'No se pudo copiar (permiso del navegador)', 'warning');
                }
            };

            document.getElementById('btnCompletar').onclick = async () => {
                const ok = await cambiarEstatusCita(event, "COMPLETADA");
                if (ok) modal.hide();
            };

            document.getElementById('btnCancelar').onclick = async () => {
                const ok = await cambiarEstatusCita(event, "CANCELADA");
                if (ok) modal.hide();
            };

            document.getElementById('btnPendiente').onclick = async () => {
                const ok = await cambiarEstatusCita(event, "PENDIENTE");
                if (ok) modal.hide();
            };
            modal.show();
        }

        async function loadEvents(fetchInfo) {
            // end exclusivo (+1 día) para que no se pierda el último día
            const start = fetchInfo.startStr;
            const endDate = new Date(fetchInfo.end);
            endDate.setDate(endDate.getDate() + 1);
            const end = endDate.toISOString().substring(0, 10);
            const url = `${API_CITAS}?start=${encodeURIComponent(start)}&end=${encodeURIComponent(end)}`;
            const res = await fetch(url);
            const data = await res.json();
            allEventsCache = data;
            paintFromCache();
        }

        // ✅ Drag&Drop / Resize -> Reprogramar en BD + WhatsApp opcional
        async function reprogramarCita(ev) {
            const idCita = ev.id;
            const start = ev.start;
            const end = ev.end;
            if (!start) return false;
            const p = ev.extendedProps || {};
            const fecha = toYMD(start);
            const hi = start.toTimeString().slice(0, 8);
            let hf = "00:00:00";
            if (end) {
                hf = end.toTimeString().slice(0, 8);
            } else {
                hf = (p.HoraFin ? String(p.HoraFin).slice(0, 8) : hi);
            }

            const payload = new URLSearchParams();
            payload.set("idCita", idCita);
            payload.set("fecha", fecha);
            payload.set("horaInicio", hi);
            payload.set("horaFin", hf);

            const res = await fetch(API_REPROG, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: payload.toString()
            });

            let json;
            try {
                json = await res.json();
            } catch (e) {
                Swal.fire("Error", "Respuesta inválida del servidor (no JSON).", "error");
                return false;
            }

            if (!json.ok) {
                Swal.fire("Error", json.msg || "No se pudo reprogramar", "error");
                return false;
            }

            // ✅ actualiza props
            ev.setExtendedProp("Fecha", fecha);
            ev.setExtendedProp("HoraInicio", hi);
            ev.setExtendedProp("HoraFin", hf);

            // ✅ info del backend (o fallback a props)
            const info = json.data || {};
            const alias = (info.alias || p.AliasCliente || "cliente");
            const servicio = (info.servicio || p.NombreServicio || "servicio");
            const telRaw = (info.telefono || p.TelefonoCliente || "").toString();
            const telWa = normalizarTelefono(telRaw); // SOLO números, con país incluido
            const fechaTxt = (info.fecha || fecha);
            const horaTxt = `${(info.horaInicio || hi).slice(0,5)} - ${(info.horaFin || hf).slice(0,5)}`;
            // ✅ arma mensaje primero (ANTES de usarlo)
            const msg =
                `Hola ${alias} 👋
Te avisamos que tu cita fue *reprogramada* por el barbero.

✂️ Servicio: ${servicio}
📅 Nueva fecha: ${fechaTxt}
⏰ Nuevo horario: ${horaTxt}

Si tienes algún inconveniente, por favor responde este mensaje.`;

            // ✅ URL WhatsApp (sin adivinar país)
            let waUrl = "";
            if (telWa.length >= 8 && telWa.length <= 15) {
                waUrl = `https://wa.me/${telWa}?text=${encodeURIComponent(msg)}`;
            }

            const result = await Swal.fire({
                icon: "success",
                title: "Cita reprogramada ✅",
                html: `
      <div style="text-align:left">
        <div><b>Cliente:</b> ${alias}</div>
        <div><b>Servicio:</b> ${servicio}</div>
        <div><b>Nueva fecha:</b> ${fechaTxt}</div>
        <div><b>Horario:</b> ${horaTxt}</div>
        <hr style="opacity:.2">
        <div style="opacity:.9">¿Deseas avisarle al cliente por WhatsApp?</div>
      </div>
    `,
                showCancelButton: true,
                confirmButtonText: "📱 Avisar por WhatsApp",
                cancelButtonText: "Cerrar",
                confirmButtonColor: "#25D366",
                cancelButtonColor: "#334155"
            });

            if (result.isConfirmed) {
                if (!waUrl) {
                    Swal.fire("Teléfono inválido", "El teléfono no está en formato internacional (con país).", "warning");
                    return true;
                }
                window.open(waUrl, "_blank");
            }
            return true;
        }

        function colorByStatus(st) {
            st = (st || "").toUpperCase();
            if (st === "COMPLETADA") return "#2A9D8F";
            if (st === "CANCELADA") return "#6C757D";
            return "#E9C46A"; // PENDIENTE
        }

        async function cambiarEstatusCita(ev, nuevoEstatus) {
            const idCita = ev.id;

            const payload = new URLSearchParams();
            payload.set("idCita", idCita);
            payload.set("estado", nuevoEstatus);
            const res = await fetch(API_ESTADO, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: payload.toString()
            });

            let json;
            try {
                json = await res.json();
            } catch (e) {
                const txt = await res.text();
                console.log("API_ESTADO status:", res.status);
                console.log("API_ESTADO response:", txt);
                Swal.fire("Error", "Respuesta inválida del servidor. Revisa Console (F12).", "error");
                return false;
            }

            if (!json.ok) {
                Swal.fire("Error", json.msg || "No se pudo cambiar el estatus", "error");
                return false;
            }

            // ✅ datos devueltos por backend
            const info = json.data || {};
            const alias = (info.alias || (ev.extendedProps?.AliasCliente) || "cliente");
            const telRaw = (info.telefono || (ev.extendedProps?.TelefonoCliente) || "").toString();
            const servicio = (info.servicio || (ev.extendedProps?.NombreServicio) || "servicio");
            const fecha = (info.fecha || (ev.extendedProps?.Fecha) || "");
            const hi = (info.horaInicio || (ev.extendedProps?.HoraInicio) || "");
            const hf = (info.horaFin || (ev.extendedProps?.HoraFin) || "");

            // ✅ actualiza props del evento (para filtros/modal/lista)
            ev.setExtendedProp("EstatusAdmin", nuevoEstatus);

            // ✅ cambia color del evento en calendario
            ev.setProp("color", colorByStatus(nuevoEstatus));

            // ✅ también actualiza cache (para lista del día/filtros)
            allEventsCache = allEventsCache.map(e => {
                if (String(e.id) === String(idCita)) {
                    e.extendedProps = e.extendedProps || {};
                    e.extendedProps.EstatusAdmin = nuevoEstatus;
                    e.color = colorByStatus(nuevoEstatus);
                }
                return e;
            });

            paintFromCache(); // repinta respetando filtros

            // ✅ refresca burbuja de notificaciones (ya que insertaste una)
            if (typeof refreshBellCount === "function") {
                refreshBellCount();
            }

            // ✅ SOLO si es CANCELADA -> preguntar WhatsApp al cliente
            if (String(nuevoEstatus).toUpperCase() === "CANCELADA") {
                const telWa = normalizarTelefono(telRaw); // deja solo números

                const horaTxt = `${(hi || "").slice(0,5)} - ${(hf || "").slice(0,5)}`;

                const msg =
                    `Hola ${alias} 👋
Te avisamos que tu cita fue *CANCELADA*.

✂️ Servicio: ${servicio}
📅 Fecha: ${fecha}
⏰ Hora: ${horaTxt}

Si deseas reprogramar, por favor responde este mensaje.`;

                let waUrl = "";
                if (telWa.length >= 8 && telWa.length <= 15) {
                    waUrl = `https://wa.me/${telWa}?text=${encodeURIComponent(msg)}`;
                }

                const r = await Swal.fire({
                    icon: "warning",
                    title: "Cita cancelada ❌",
                    html: `
        <div style="text-align:left">
          <div><b>Cliente:</b> ${alias}</div>
          <div><b>Servicio:</b> ${servicio}</div>
          <div><b>Fecha:</b> ${fecha}</div>
          <div><b>Horario:</b> ${horaTxt}</div>
          <hr style="opacity:.2">
          <div style="opacity:.9">¿Deseas avisarle al cliente por WhatsApp?</div>
        </div>
      `,
                    showCancelButton: true,
                    confirmButtonText: "📱 Avisar por WhatsApp",
                    cancelButtonText: "Cerrar",
                    confirmButtonColor: "#25D366",
                    cancelButtonColor: "#334155"
                });

                if (r.isConfirmed) {
                    if (!waUrl) {
                        Swal.fire("Teléfono inválido", "El teléfono del cliente no está en formato internacional (con país).", "warning");
                    } else {
                        window.open(waUrl, "_blank");
                    }
                }
                return true;
            }
            // ✅ para otros estados solo confirmación normal
            Swal.fire("Listo", `Estatus actualizado a ${nuevoEstatus}`, "success");
            return true;
        }
        document.addEventListener('DOMContentLoaded', () => {
            const calEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                locale: 'es',
                firstDay: 1,
                nowIndicator: true,
                selectable: true,
                editable: true,
                eventStartEditable: true,
                eventDurationEditable: true,
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                // ✅ IMPORTANTE: aquí NO usamos successCallback para pintar
                // (porque nosotros pintamos desde cache con paintFromCache)
                events: async (fetchInfo, successCallback, failureCallback) => {
                    try {
                        await loadEvents(fetchInfo);
                        successCallback([]); // ✅ evita duplicados
                    } catch (e) {
                        failureCallback(e);
                    }
                },
                dateClick: (info) => renderListForDay(info.dateStr),
                eventClick: (info) => openModal(info.event),
                // ✅ ESTE ES EL QUE TE FALTABA
                eventDrop: async (info) => {
                    const ok = await reprogramarCita(info.event);
                    if (!ok) info.revert();
                },
                eventResize: async (info) => {
                    const ok = await reprogramarCita(info.event);
                    if (!ok) info.revert();
                }
            });
            calendar.render();
            // filtros
            document.getElementById('filtroEstatus').addEventListener('change', paintFromCache);
            document.getElementById('filtroTexto').addEventListener('input', () => {
                clearTimeout(window.__t);
                window.__t = setTimeout(paintFromCache, 250);
            });
            document.getElementById('btnReset').addEventListener('click', () => {
                document.getElementById('filtroEstatus').value = '';
                document.getElementById('filtroTexto').value = '';
                paintFromCache();
            });
            document.getElementById('btnHoy').addEventListener('click', () => {
                const ymd = toYMD(new Date());
                calendar.today();
                renderListForDay(ymd);
            });

            const API_NOTIFS_COUNT = "../../Backend/Admin/notifs_count.php";

            function setBellCount(n) {
                const el = document.getElementById("bellCount");
                if (!el) return;
                const num = Number(n || 0);
                if (num > 0) {
                    el.style.display = "";
                    el.textContent = (num > 99) ? "99+" : String(num);
                } else {
                    el.style.display = "none";
                    el.textContent = "0";
                }
            }

            async function refreshBellCount() {
                try {
                    const res = await fetch(API_NOTIFS_COUNT, {
                        cache: "no-store"
                    });
                    const json = await res.json();
                    if (!json.ok) return;
                    setBellCount(json.unread);
                } catch (e) {
                    // silencioso (no molestamos al admin)
                }
            }

            const API_NOTIFS = "../../Backend/Admin/notifs_listar.php";
            const API_NOTIFS_LEER = "../../Backend/Admin/notifs_marcar_leidas.php";

            function iconNotif(tipo) {
                tipo = (tipo || "").toUpperCase();
                if (tipo.includes("CANCEL")) return "❌";
                if (tipo.includes("CAMBIO_FECHA")) return "📅";
                if (tipo.includes("COMPLET")) return "✅";
                return "🔔";
            }

            function esc(s) {
                return (s ?? "").toString()
                    .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;").replace(/'/g, "&#039;");
            }

            async function cargarNotifs() {
                const res = await fetch(`${API_NOTIFS}?top=50`);
                const json = await res.json();
                if (!json.ok) throw new Error(json.msg || "No se pudieron cargar notificaciones");
                return json;
            }

            async function marcarLeidas(ids = [], all = false) {
                const payload = new URLSearchParams();
                if (all) payload.set("all", "1");
                else payload.set("ids", ids.join(","));
                const res = await fetch(API_NOTIFS_LEER, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: payload.toString()
                });
                const json = await res.json();
                if (!json.ok) throw new Error(json.msg || "No se pudieron marcar leídas");
                return true;
            }

            async function abrirCampana() {
                let data;
                try {
                    data = await cargarNotifs();
                } catch (e) {
                    Swal.fire("Error", e.message, "error");
                    return;
                }

                const items = data.items || [];
                const unread = data.unread ?? 0;
                if (!items.length) {
                    await Swal.fire({
                        icon: "info",
                        title: "Notificaciones",
                        text: "No hay notificaciones por ahora.",
                        background: "#0b1220",
                        color: "#fff"
                    });
                    return;
                }
                const html = `
    <div style="text-align:left; max-height:360px; overflow:auto; padding-right:6px;">
      ${items.map(n => {
        const ico = iconNotif(n.Tipo);
        const bold = n.Leida ? "" : "font-weight:700";
        const bg = n.Leida ? "rgba(255,255,255,.06)" : "rgba(255,255,255,.10)";
        return `
          <div style="padding:10px 12px; border-radius:12px; margin-bottom:10px; background:${bg}; border:1px solid rgba(255,255,255,.08); ${bold}">
            <div style="display:flex; justify-content:space-between; gap:12px;">
              <div>
                <div>${ico} ${esc(n.Titulo)}</div>
                <div style="opacity:.85; margin-top:6px;">${esc(n.Mensaje)}</div>
                <div style="opacity:.6; font-size:.85rem; margin-top:6px;">${esc(n.Fecha)}</div>
              </div>
              <div style="white-space:nowrap; opacity:.7;">
                ${n.Leida ? "Leída" : "Nueva"}
              </div>
            </div>
          </div>
        `;
      }).join("")}
    </div>
  `;

                const result = await Swal.fire({
                    title: `🔔 Notificaciones ${unread ? `(${unread} nuevas)` : ""}`,
                    html,
                    width: 720,
                    showCancelButton: true,
                    confirmButtonText: "Marcar todas leídas",
                    cancelButtonText: "Cerrar",
                    confirmButtonColor: "#2563EB",
                    cancelButtonColor: "#334155",
                    background: "#0b1220",
                    color: "#fff"
                });

                if (result.isConfirmed) {
                    try {
                        await marcarLeidas([], true);
                        await refreshBellCount(); // ✅ reinicia burbuja
                        Swal.fire("Listo", "Se marcaron como leídas ✅", "success");
                    } catch (e) {
                        Swal.fire("Error", e.message, "error");
                    }
                }
            }

            document.getElementById("btnBell").addEventListener("click", abrirCampana);
            refreshBellCount();
            setInterval(refreshBellCount, 8000); // cada 8s (puedes 10s/15s)


        });
    </script>

    <script>
        async function refrescarBellCount() {
            try {
                const res = await fetch(`${API_NOTIFS}?unread=1&top=1`);
                const json = await res.json();
                const n = json.unread ?? 0;
                const el = document.getElementById("bellCount");
                if (!el) return;
                if (n > 0) {
                    el.style.display = "";
                    el.textContent = n;
                } else {
                    el.style.display = "none";
                    el.textContent = "0";
                }
            } catch (e) {}
        }
        refrescarBellCount();
        setInterval(refrescarBellCount, 15000); // cada 15s
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
    </script>
</body>

</html>