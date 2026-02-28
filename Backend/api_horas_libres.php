<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../conexion.php";

$idServicio = isset($_GET['servicio']) ? (int)$_GET['servicio'] : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

if ($idServicio <= 0) {
  echo json_encode(["duracion" => 0, "base" => 0, "horas" => [], "msg" => "Servicio inválido"]);
  exit;
}

/* =========================
   1) Duración del servicio
   ========================= */
$sqlS = "SELECT TimpoServicio FROM TBL_BarberServicios WHERE IdServ=? AND Status=1";
$stmtS = sqlsrv_query($conn, $sqlS, [$idServicio]);
$serv = sqlsrv_fetch_array($stmtS, SQLSRV_FETCH_ASSOC);

if (!$serv) {
  echo json_encode(["duracion" => 0, "base" => 0, "horas" => [], "msg" => "Servicio no válido"]);
  exit;
}

$txt = strtoupper(trim($serv['TimpoServicio'])); // "30 MIN" / "1 HRS"
$duracion = 30;

if (strpos($txt, 'MIN') !== false) {
  $duracion = (int)preg_replace('/\D+/', '', $txt);
} elseif (strpos($txt, 'HRS') !== false) {
  $duracion = (int)preg_replace('/\D+/', '', $txt) * 60;
}
if ($duracion <= 0) $duracion = 30;

/* =========================
   2) Día de la semana (tu tabla)
   ========================= */
$dowEng = strtoupper(date('l', strtotime($fecha)));
$map = [
  "MONDAY" => "LUNES",
  "TUESDAY" => "MARTES",
  "WEDNESDAY" => "MIERCOLES",
  "THURSDAY" => "JUEVES",
  "FRIDAY" => "VIERNES",
  "SATURDAY" => "SABADO",
  "SUNDAY" => "DOMINGO"
];
$dia = $map[$dowEng] ?? "DOMINGO";

/* =========================
   3) Si es domingo -> vacío
   ========================= */
if ($dia === "DOMINGO") {
  echo json_encode(["duracion" => $duracion, "base" => 0, "horas" => [], "msg" => "Domingo sin servicio"]);
  exit;
}

/* =========================
   4) Horario del día + comida
   ========================= */
$sqlH = "SELECT HoraInicio, HoraFin, HorarioComida, Status
         FROM TBL_HorariosServicio
         WHERE DiaSemana=?";
$stmtH = sqlsrv_query($conn, $sqlH, [$dia]);
$h = sqlsrv_fetch_array($stmtH, SQLSRV_FETCH_ASSOC);

if (!$h || (int)$h['Status'] === 0) {
  echo json_encode(["duracion" => $duracion, "base" => 0, "horas" => [], "msg" => "Sin servicio este día"]);
  exit;
}

$horaInicio = $h['HoraInicio']->format('H:i:s');
$horaFin    = $h['HoraFin']->format('H:i:s');

// comida: "14:30:00 - 16:00:00" o NULL
$comidaIni = null;
$comidaFin = null;
if (!empty($h['HorarioComida'])) {
  $p = explode('-', $h['HorarioComida']);
  if (count($p) == 2) {
    $comidaIni = trim($p[0]);
    $comidaFin = trim($p[1]);
    // por si viene "14:30:00" o "14:30"
    $comidaIni = substr($comidaIni, 0, 5);
    $comidaFin = substr($comidaFin, 0, 5);
  }
}

/* =========================
   5) Slots base (por qué 15)
   =========================
   - 15 permite ofrecer 10/20/45 sin perder horarios.
   - 30 es más simple pero limita.
*/
$base = 15;

// Si quieres hacerlo “semi-dinámico”:
// - si dura 60 → base 30
// - si dura 10/15/20/45 → base 15
if ($duracion >= 60) $base = 30;

/* =========================
   6) Citas ocupadas del día
   ========================= */
$sqlC = "SELECT HoraInicio, HoraFin
         FROM TBL_CitasReserbaciones
         WHERE FechaRecerbacion = ?
           AND Estado <> 'CANCELADA'";
$stmtC = sqlsrv_query($conn, $sqlC, [$fecha]);

$ocupadas = [];
while ($r = sqlsrv_fetch_array($stmtC, SQLSRV_FETCH_ASSOC)) {
  $ocupadas[] = [
    "ini" => $r['HoraInicio']->format('H:i'),
    "fin" => $r['HoraFin']->format('H:i')
  ];
}

/* Helpers */
$toMin = function ($hm) {
  $hm = substr($hm, 0, 5); // HH:MM
  return ((int)substr($hm, 0, 2) * 60) + (int)substr($hm, 3, 2);
};

/* =========================
   7) Generar horas libres
   ========================= */
$iniM = $toMin(substr($horaInicio, 0, 5));
$finM = $toMin(substr($horaFin, 0, 5));

$cIniM = $comidaIni ? $toMin($comidaIni) : null;
$cFinM = $comidaFin ? $toMin($comidaFin) : null;

$horas = [];

for ($t = $iniM; $t + $duracion <= $finM; $t += $base) {

  $hi = sprintf("%02d:%02d", intdiv($t, 60), $t % 60);
  $tf = $t + $duracion;
  $hf = sprintf("%02d:%02d", intdiv($tf, 60), $tf % 60);

  // 7.1) Bloquear comida (si se cruza)
  if ($cIniM !== null && $cFinM !== null) {
    if ($t < $cFinM && $tf > $cIniM) {
      continue;
    }
  }

  // 7.2) Bloquear ocupadas (si se cruza)
  $ok = true;
  foreach ($ocupadas as $o) {
    $oi = $toMin($o['ini']);
    $of = $toMin($o['fin']);
    if ($t < $of && $tf > $oi) {
      $ok = false;
      break;
    }
  }
  if (!$ok) continue;

  $horas[] = ["hora" => $hi, "horaFin" => $hf];
}

echo json_encode([
  "duracion" => $duracion,
  "base" => $base,
  "horario" => [
    "inicio" => substr($horaInicio, 0, 5),
    "fin" => substr($horaFin, 0, 5),
    "comidaIni" => $comidaIni,
    "comidaFin" => $comidaFin
  ],
  "horas" => $horas
]);
