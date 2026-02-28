<?php
include_once "../../../conexion.php";

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
error_reporting(0);

function ok($data)
{
    echo json_encode(["ok" => true] + $data, JSON_UNESCAPED_UNICODE);
    exit;
}
function fail($msg)
{
    echo json_encode(["ok" => false, "msg" => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

$tz = new DateTimeZone("America/Mexico_City");
$today = new DateTime("now", $tz);
$todayStr = $today->format("Y-m-d");

// Semana (lunes a domingo)
$weekStart = (clone $today);
$weekStart->modify('monday this week');
$weekStartStr = $weekStart->format("Y-m-d");

$weekEnd = (clone $weekStart);
$weekEnd->modify('+7 day'); // exclusivo
$weekEndStr = $weekEnd->format("Y-m-d");

// Mes
$monthStart = new DateTime($today->format("Y-m-01"), $tz);
$monthStartStr = $monthStart->format("Y-m-d");

$monthEnd = (clone $monthStart);
$monthEnd->modify('+1 month'); // exclusivo
$monthEndStr = $monthEnd->format("Y-m-d");

// KPIs: ingresos
$sqlIngresos = "SELECT
  SUM(CASE WHEN EstatusAdmin='COMPLETADA' AND FechaRecerbacion = ? THEN TRY_CONVERT(decimal(18,2), CostoServicio) ELSE 0 END) AS ingreso_hoy,
  SUM(CASE WHEN EstatusAdmin='COMPLETADA' AND FechaRecerbacion >= ? AND FechaRecerbacion < ? THEN TRY_CONVERT(decimal(18,2), CostoServicio) ELSE 0 END) AS ingreso_semana,
  SUM(CASE WHEN EstatusAdmin='COMPLETADA' AND FechaRecerbacion >= ? AND FechaRecerbacion < ? THEN TRY_CONVERT(decimal(18,2), CostoServicio) ELSE 0 END) AS ingreso_mes,

  COUNT(CASE WHEN EstatusAdmin='COMPLETADA' AND FechaRecerbacion >= ? AND FechaRecerbacion < ? THEN 1 END) AS citas_semana,
  COUNT(CASE WHEN EstatusAdmin='CANCELADA'  AND FechaRecerbacion >= ? AND FechaRecerbacion < ? THEN 1 END) AS canceladas_mes,
  COUNT(CASE WHEN FechaRecerbacion >= ? AND FechaRecerbacion < ? THEN 1 END) AS total_mes
FROM dbo.TBL_CitasReserbaciones;";

$params = [
    $todayStr,
    $weekStartStr,
    $weekEndStr,
    $monthStartStr,
    $monthEndStr,
    $weekStartStr,
    $weekEndStr,
    $monthStartStr,
    $monthEndStr,
    $monthStartStr,
    $monthEndStr,
];

$stmt = sqlsrv_query($conn, $sqlIngresos, $params);
if (!$stmt) {
    fail("No se pudieron calcular KPIs");
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$ingHoy = floatval($row["ingreso_hoy"] ?? 0);
$ingSem = floatval($row["ingreso_semana"] ?? 0);
$ingMes = floatval($row["ingreso_mes"] ?? 0);

$citasSem = intval($row["citas_semana"] ?? 0);
$ticketSem = $citasSem > 0 ? ($ingSem / $citasSem) : 0;

$cancelMes = intval($row["canceladas_mes"] ?? 0);
$totalMes = intval($row["total_mes"] ?? 0);
$porcCancel = $totalMes > 0 ? round(($cancelMes / $totalMes) * 100, 1) : 0;

// Top servicio del mes (por ingresos)
$sqlTop = "SELECT TOP 1
  NombreServicio,
  SUM(TRY_CONVERT(decimal(18,2), CostoServicio)) AS total
FROM dbo.TBL_CitasReserbaciones
WHERE EstatusAdmin='COMPLETADA'
  AND FechaRecerbacion >= ? AND FechaRecerbacion < ?
GROUP BY NombreServicio
ORDER BY total DESC;";
$stmt2 = sqlsrv_query($conn, $sqlTop, [$monthStartStr, $monthEndStr]);
$topServicio = "—";
$topTotal = 0;
if ($stmt2 && ($r2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))) {
    $topServicio = (string)$r2["NombreServicio"];
    $topTotal = floatval($r2["total"]);
}

ok([
    "ingreso_hoy" => $ingHoy,
    "ingreso_semana" => $ingSem,
    "ingreso_mes" => $ingMes,
    "citas_semana" => $citasSem,
    "ticket_prom_semana" => $ticketSem,
    "canceladas_mes" => $cancelMes,
    "porcentaje_canceladas_mes" => $porcCancel,
    "top_servicio_mes" => $topServicio,
    "top_servicio_mes_total" => $topTotal,
    "rangos" => [
        "today" => $todayStr,
        "week_start" => $weekStartStr,
        "week_end_exclusive" => $weekEndStr,
        "month_start" => $monthStartStr,
        "month_end_exclusive" => $monthEndStr
    ]
]);
